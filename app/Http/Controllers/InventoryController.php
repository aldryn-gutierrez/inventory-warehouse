<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\InventoryStatus;
use App\Models\StockInformation;
use App\Repositories\InventoryHistoryRepository;
use App\Repositories\InventoryRepository;
use App\Repositories\StockInformationRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Validator;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function report(InventoryRepository $inventoryRepository)
    {
        return Response::json(
            $inventoryRepository->getInventorySummary(),
            200,
            ['Content-disposition' => 'attachment; filename="inventory-report.json"']
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        InventoryRepository $inventoryRepository,
        StockInformationRepository $stockInformationRepository
    ) {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|int|exists:products,id',
            'distribution_center_id' => 'required|int|exists:distribution_centers,id',
            'quantity' => 'required|int|min:0',
        ]);

        if ($errorMessages = $validator->errors()->all()) {
            return Response::json(['errors' => implode(', ', $errorMessages)], 400);
        }

        $inventoryExists = $inventoryRepository->existsByFields([
            'product_id' => $request->product_id,
            'distribution_center_id' => $request->distribution_center_id,
        ]);

        if ($inventoryExists) {
            return Response::json(
                ['errors' => 'Inventory with same product and distribution center exists'],
                400
            );
        }

        $stockInformation = $stockInformationRepository->create([
            'initial_quantity' => $request->quantity,
            'remaining_quantity' => $request->quantity,
        ]);

        $inventory = $inventoryRepository->create([
            'product_id' => $request->product_id,
            'distribution_center_id' => $request->distribution_center_id,
            'stock_information_id' => $stockInformation->id,
        ]);

        // Improvement: Implement Transformers instead 
        // of just returning array form
        return Response::json($inventory->fresh()->toArray());
    }

    /**
     * Adjusts the inventory when an item is shipped, item is added,
     * item is removed, and item is reserved
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adjust(
        Request $request,
        InventoryRepository $inventoryRepository,
        StockInformationRepository $stockInformationRepository,
        InventoryHistoryRepository $inventoryHistoryRepository
    ) {
        $validator = Validator::make($request->all(), [
            'inventory_id' => 'required|exists:inventories,id',
            'inventory_status_id' => 'required|exists:inventory_statuses,id',
        ]);

        if ($errorMessages = $validator->errors()->all()) {
            return Response::json(['errors' => implode(', ', $errorMessages)], 400);
        }

        $inventory = $inventoryRepository->find($request->inventory_id);
        $stockInformation = $inventory->stockInformation;

        // Calculate how much remaining quantity
        $remainingQuantity = $stockInformation->calculateRemainingQuantityByInventoryStatusId($request->inventory_status_id);

        // Update Stock Information
        $stockInformationRepository->update(['remaining_quantity' => $remainingQuantity], $stockInformation->id);

        // Insert in InventoryHistory
        $inventoryHistoryRepository->create([
            'inventory_id' => $inventory->id,
            'inventory_status_id' => $request->inventory_status_id
        ]);

        return Response::json([], 204);
    }
}

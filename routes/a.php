        Route::get('OrderItem', [OrderItemController::class, 'index']);
        Route::get('OrderItem/{id}', [OrderItemController::class, 'show']);
        Route::post('OrderItem', [OrderItemController::class, 'store']);
        Route::put('OrderItem/{id}/show', [OrderItemController::class, 'update']);
        Route::delete('OrderItem/{id}/delete', [OrderItemController::class, 'destroy']);
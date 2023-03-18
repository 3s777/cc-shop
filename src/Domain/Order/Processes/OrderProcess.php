<?php

namespace Domain\Order\Processes;

use Domain\Order\Events\OrderCreated;
use Domain\Order\Models\Order;
use DomainException;
use Illuminate\Routing\Pipeline;
use Support\Transaction;
use Throwable;

class OrderProcess
{
    protected array $processes = [];

    public function __construct(
        protected Order $order
    )
    {

    }

    public function processes(array $processes): self
    {
        $this->processes = $processes;

        return $this;
    }

    public function run(): Order
    {
        return Transaction::run(function() {
          return app(Pipeline::class)
              ->send($this->order)
              ->through($this->processes)
              ->thenReturn();
        }, function (Order $order) {
            flash()->info('Good #' . $order->id);

            event(new OrderCreated($order));
        }, function(Throwable $e) {
            // Нельзя показывать сообщение, нужно обернуть и проверить не на продакшене ли мы. Если продакшн, то выдать строковое сообщение
            throw new DomainException($e->getMessage());
        });
    }

}

<?php

namespace App\View;

use App\Enums\AlertType;

readonly class AlertView
{
    /**
     * @param string $message Il messaggio da visualizzare
     * @param AlertType $type Tipologia di alert, default AlertType::Success
     * @param string $width Larghezza dell'alert espressa in valore compatibile CSS.
     */
    function __construct(
        private string $message,
        private AlertType $type = AlertType::Success,
        private string $width = '50%'
    )
    {
    }

    function __toString()
    {
        return view('partials.alert', [
            'message' => $this->message,
            'size' => $this->width,
            'type' => $this->type->value,
        ])
            ->render();
    }
}

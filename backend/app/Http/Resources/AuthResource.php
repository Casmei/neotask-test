<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    private string $token;
    private string $message;

    public function __construct($resource, string $token, string $message)
    {
        parent::__construct($resource);
        $this->token = $token;
        $this->message = $message;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $response = [
            "id" => $this->getId(),
            "name" => $this->getName(),
            "email" => $this->getEmail(),
            "isAdmin" => $this->getIsAdmin(),
            "token" => $this->token,
            "message" => "Registro realizado com sucesso",
        ];

        if (isset($this->message)) {
            $response["message"] = $this->message;
        }

        return $response;
    }
}

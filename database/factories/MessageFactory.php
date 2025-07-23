<?php

namespace Database\Factories;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition(): array
    {
        $sender = User::factory()->create();
        $receiver = User::factory()->create();

        return [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'subject' => fake()->sentence(),
            'content' => fake()->paragraph(3),
            'read_at' => fake()->optional(0.7)->dateTimeThisYear(), // 70% de chance d'être lu
        ];
    }
}
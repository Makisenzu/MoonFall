<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewsAlert implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public $news)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('news.alert.'. strtolower($this->news->audience)),
        ];
    }
    public function broadcastAs(): string
    {
        return 'news.alert';
    }
    public function broadcastWith(): array
    {
        return [
            'id' => $this->news->id,
            'title' => $this->news->news_name,
            'description' => $this->news->description,
            'urgency' => $this->news->urgency,
            'audience' => $this->news->audience,
            'created_at' => $this->news->created_at->toDateTimeString()
        ];
    }
}

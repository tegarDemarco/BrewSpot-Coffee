<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppNotification extends Model {
    protected $table = 'app_notifications';
    protected $fillable = ['user_id', 'title', 'message', 'type', 'icon', 'url', 'is_read'];
    protected $casts = ['is_read' => 'boolean'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public static function notifyUser(int $userId, string $title, string $message, string $type = 'info', string $icon = '🔔', ?string $url = null): void {
        self::create([
            'user_id' => $userId,
            'title'   => $title,
            'message' => $message,
            'type'    => $type,
            'icon'    => $icon,
            'url'     => $url,
            'is_read' => false,
        ]);
    }

    public static function notifyOwners(string $title, string $message, string $type = 'info', string $icon = '🔔', ?string $url = null): void {
        $owners = User::where('role', 'owner')->where('is_active', true)->pluck('id');
        foreach ($owners as $ownerId) {
            self::notifyUser($ownerId, $title, $message, $type, $icon, $url);
        }
    }
}


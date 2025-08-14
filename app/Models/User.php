<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\LookupData\Department;
use App\Models\LookupData\Position;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected $fillable = [
        'name', 'email', 'password', 'department_id', 'position_id', 'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    // Validation rules
    public static function validateStore($data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
        ])->validate();
    }

    public static function validateUpdate($id, $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($id),
            ],
            'password' => 'nullable|string|min:6',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
        ])->validate();
    }

    // CRUD Methods
    public static function getAllUsers()
    {
        return self::with(['department', 'position'])->latest()->get();
    }

    public static function storeUser($data)
    {
        try {
            $validated = self::validateStore($data);
            $validated['password'] = Hash::make($validated['password']);
            return self::create($validated);
        } catch (\Throwable $e) {
            Log::error('User store error: ' . $e->getMessage());
            throw $e;
        }
    }

    public static function updateUser($id, $data)
    {
        try {
            $validated = self::validateUpdate($id, $data);
            $user = self::findOrFail($id);

            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);
            return $user;
        } catch (\Throwable $e) {
            Log::error("User update error for ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public static function deleteUser($id)
    {
        try {
            return self::destroy($id);
        } catch (\Throwable $e) {
            Log::error("User delete error for ID $id: " . $e->getMessage());
            throw $e;
        }
    }

    public static function assignRoleById($userId, $roleName)
    {
        try {
            $user = self::findOrFail($userId);
            $user->syncRoles([$roleName]);

            return true;
        } catch (\Throwable $e) {
            Log::error('Failed to assign role: ' . $e->getMessage());
            return false;
        }
    }

}

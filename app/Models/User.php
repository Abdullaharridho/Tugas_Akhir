<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mapel',
        'password',
        'tipeuser',
    ];

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
    public function mapel()
    {
        return $this->belongsToMany(MataPelajaran::class, 'mapel_user', 'id_guru', 'mata_pelajaran_id');
    }
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->usertipe == 'admin') {
            return redirect('admin/dashboard');
        }
        return $next($request);
    }
}

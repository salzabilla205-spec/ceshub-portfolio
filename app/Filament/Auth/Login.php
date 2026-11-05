<?php
namespace App\Filament\Auth;
use Illuminate\Contracts\Support\Htmlable;
class Login extends \Filament\Auth\Pages\Login {
 public function getTitle(): string|Htmlable { return 'Login Admin'; }
 public function getHeading(): string|Htmlable|null { return 'Login Admin'; }
}

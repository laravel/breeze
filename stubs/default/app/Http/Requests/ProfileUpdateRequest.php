<?php

namespace App\Http\Requests;

use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Rule as ValidationRule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<ValidationRule|string>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        if (
            $user instanceof User
        ) {
            return [
                'name' => ['string', 'max:255'],
                'email' => ['email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            ];
        }

        return [];
    }
}

<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;
use Illuminate\Support\Facades\Auth;

/**
 * @bodyParam data.attributes.title string The ticket's title. No-example
 * @bodyParam data.relationships.author.data.id integer The author id. No-example
 */
class StoreTicketRequest extends BaseTicketRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Sanctum middleware protecting these
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isTicketsController = $this->routeIs('tickets.store');
        $authorIdAttr = $isTicketsController ? 'data.relationships.author.data.id' : 'author';
        $user = Auth::user();
        $authorRule = 'required|integer|exists:users,id';

        $rules = [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.title' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $authorIdAttr => $authorRule . '|size:' . $user->id,
        ];

        if ($isTicketsController)
        {
            $rules['data.relationships'] = 'required|array';
            $rules['data.relationships.author'] = 'required|array';
            $rules['data.relationships.author.data'] = 'required|array';
        }

        if (Auth::user()->tokenCan(Abilities::CreateTicket))
        {
            $rules[$authorIdAttr] = $authorRule;
        }

        return $rules;
    }

    protected function prepareForValidation()
    {
        if ($this->routeIs('authors.tickets.store'))
        {
            $this->merge([
                'data.relationships.author.data.id' => $this->route('author'),
            ]);
        }
    }

    public function bodyParameters()
    {
        return [
            'data.attributes.title' => [
                'description' => 'The title of the ticket',
                'example' => 'No-example',
            ]
        ];
    }
}

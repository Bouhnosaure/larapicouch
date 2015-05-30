<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class LocalDataRequest extends Request {

	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules()
	{
		return [
			'alias' => 'alpha_dash',
			'ip' => 'ip',
            'plant_id' => 'alpha_dash'
		];
	}

}

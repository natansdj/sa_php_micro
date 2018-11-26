<?php
/**
 * Created by PhpStorm.
 * User: fabrizio
 * Date: 26/07/18
 * Time: 19.19
 */

namespace App\Repositories;

use Core\Repository\Eloquent\RepositoryAbstract;
use Illuminate\Support\Facades\Validator;

class InvoiceRepository extends RepositoryAbstract
{
	private static $rules = [
		'user_id' => 'required|exists:users,id',
		'name' => 'required|min:5|max:255',
		'description' => 'required|min:5',
		'harga' => 'required|numeric',
		'stock' => 'required|numeric',
	];

	private static $rules_update = [
		'user_id' => 'exists:users,id',
		'name' => 'min:5|max:255',
		'description' => 'min:5'
	];

	/**
	 * Specify Model class name
	 *
	 * @return mixed
	 */
	function model()
	{
		return 'App\Models\Invoice';
	}

	/** Validate request api
	 *
	 * @param array $request
	 * @param $type
	 * @param array $rules_specific
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function validateRequest(array $request, $type = "", array $rules_specific = [])
	{
		$rules = $this->rules($type, $rules_specific);

		if (!isset($request)) {
			return $this->response->errorNotFound();
		}

		$validator = Validator::make($request, $rules);
		if ($validator->fails()) {
			return $this->response->addData($validator->errors()->toArray())->errorNotFound();
		}

		return $this->response->success("Rules validate success");
	}

	/** Use rules based on request
	 *
	 * @param $type
	 * @param array $rules_specific
	 * @return array
	 */
	private function rules($type, array $rules_specific = [])
	{
		if(!empty($rules_specific)){
			return $rules_specific;
		}

		switch ($type) {
			case "store":
			case "create":
				$rules = self::$rules;
				break;
			case "update":
				$rules = self::$rules_update;
				break;
			default:
				$rules = self::$rules;
				break;
		}
		return $rules;
	}
}
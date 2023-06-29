<?php
namespace App\Repositories;
abstract class BaseRepository{

	protected $model;

	public function getById($id){
		return $this->model->findOrFail($id);
	}

	public function destroy($id){
		return $this->getById($id)->delete();
	}
}
<?php
namespace App\Repositories\OTManagement;

use App\Models\AttendanceManagement\Attendance;
use App\Models\OTManagement\DailyOtRequest;
use App\Models\OTManagement\MonthlyOtRequest;
use App\Models\OTManagement\MonthlyDriverOtRequest;
use App\Models\OTManagement\MonthlyOtRequestDetail;
use App\Models\OTManagement\MonthlyDriverOtRequestDetail;
use App\Models\OTManagement\AnnualOtSummary;
use App\Models\MasterManagement\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use App\Helpers\LogActivity;
use Illuminate\Support\Facades\Mail;
use App\Mail\MonthlyOTStatusMail;
use App\Mail\AccountAdminMail;
use App\Mail\ManagerAccountMail;
use DB;
use Carbon\Carbon;
//use DateTime;

class MonthlyOTRequestRepository extends BaseRepository
{
	protected $model_log;
	public function __construct(MonthlyOtRequest $ot_request)
	{
		$this->model=$ot_request;
	}

	public function index($name,$order){

		if(Auth::user()->can('ot-read-all')){
			if(Auth::user()->can('change-ot-manager-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->orderBy($name,$order);
			}
			else if(Auth::user()->can('change-ot-account-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else if(Auth::user()->can('change-ot-gm-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_requests.manager_main_status','=',1)->where('monthly_ot_requests.account_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			if(Auth::user()->can('change-ot-manager-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_ids)->orderBy($name,$order);
			}
			else if(Auth::user()->can('change-ot-account-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_id)->where('monthly_ot_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else if(Auth::user()->can('change-ot-gm-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_id)->where('monthly_ot_requests.manager_main_status','=',1)->where('monthly_ot_requests.account_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrder($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status='all',$n){
		$query=$this->index($name,$order);
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_requests.created_at AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_requests.created_at AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status!='all'){
			if(isManager())
				$query->where('monthly_ot_requests.manager_status','=',$status);
			else if(isAccountant())
				$query->where('monthly_ot_requests.account_status','=',$status);
			else if(isAdministrator())
				$query->where('monthly_ot_requests.gm_status','=',$status);
			else
				$query->where('monthly_ot_requests.gm_status','=',$status);
		}
		//$query->groupBy('monthly_ot_requests.id');
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexStaff($name,$order){

		if(Auth::user()->can('ot-read-all')){
			
			return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->orderBy($name,$order);
			
		}
		else if(Auth::user()->can('ot-read-group')){
			
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_ids)->orderBy($name,$order);
			
		}
		else{
			return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}

	public function indexOrderStaff($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$n){
		$query=$this->indexStaff("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexDeptGM($name,$order){

		if(Auth::user()->can('ot-read-all')){
			if(Auth::user()->can('change-ot-manager-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			if(Auth::user()->can('change-ot-manager-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_ids)->orderBy($name,$order);
			}
			
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}

	public function indexOrderDeptGM($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status="0",$n){
		$query=$this->indexDeptGM("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_ot_requests.manager_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_ot_requests.manager_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_ot_requests.manager_main_status','=',0);
		}
		//$query->groupBy('monthly_ot_requests.id');
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexAccount($name,$order){

		if(Auth::user()->can('ot-read-all')){
			if(Auth::user()->can('change-ot-account-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			if(Auth::user()->can('change-ot-account-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_ids)->where('monthly_ot_requests.manager_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrderAccount($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status=0,$n){
		$query=$this->indexAccount("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_ot_requests.account_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_ot_requests.account_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_ot_requests.account_main_status','=',0);
		}
		//$query->groupBy('monthly_ot_requests.id');
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function indexAdminGM($name,$order){

		if(Auth::user()->can('ot-read-all')){
			if(Auth::user()->can('change-ot-gm-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_requests.manager_main_status','=',1)->where('monthly_ot_requests.account_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			if(Auth::user()->can('change-ot-gm-status')){
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->whereIn('monthly_ot_request_details.user_id',$user_ids)->where('monthly_ot_requests.manager_main_status','=',1)->where('monthly_ot_requests.account_main_status','=',1)->orderBy($name,$order);
			}
			else{
				return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
			}
		}
		else{
			return $this->model->select('monthly_ot_requests.id as m_id','monthly_ot_requests.date','monthly_ot_requests.user_id as userid','monthly_ot_request_details.*')->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy($name,$order);
		}
		
		
		
	}	

	public function indexOrderAdminGM($name,$order,$from_date=null,$to_date=null,$employee=null,$department=null,$status=0,$n){
		$query=$this->indexAdminGM("apply_date","asc");
		//$query->where('monthly_ot_request_details.daily_request','=',0);
		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_requests.user_id');
		
		if($from_date!=null){
			$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_requests.date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_requests.user_id','=',$employee);
			});
		}
		if($department!=null){
			$query->where(function($q) use ($department){
				$q->where('users.department_id','=',$department);
			});
		}
		if($status){
			if($status=="all"){
				$query->whereIn('monthly_ot_requests.gm_main_status',[0,1,2]);
			}
			else{
				$query->where('monthly_ot_requests.gm_main_status','=',$status);
			}
			
		}
		else{
			$query->where('monthly_ot_requests.gm_main_status','=',0);
		}
		
        $result = $query->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->date.'_'.$value->userid][] = $value;
		}
		return $request_array;


	}

	public function changeStatus($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_requests = isset($input['all_request'])?$input['all_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyOtRequest::findOrFail($m_id);

			if($type=="manager"){
				$monthlyrequest->manager_reason = $reason;
				$monthlyrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
				$monthlyrequest->save();
			}
			else if($type=="accountant"){
				$monthlyrequest->account_reason = $reason;
				$monthlyrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
				$monthlyrequest->save();
			}
			else if($type=="gm"){
				$monthlyrequest->gm_reason = $reason;
				$monthlyrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
				$monthlyrequest->save();
			}

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_requests)){
	    			$status = 1;
	    		}
	    		else{
	    			$status = 2;
	    		}
	    		$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

	    		if($type=="manager"){
					$detailrequest->manager_status = $status;
					$detailrequest->manager_status_reason = $reason;
					$detailrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->manager_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];
					if($status==2){
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->manager_change_date;
						$dailyotrequest->status_change_by = $detailrequest->manager_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
				}
				else if($type=="accountant"){
					$detailrequest->account_status = $status;
					$detailrequest->account_status_reason = $reason;
					$detailrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->account_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->account_change_date;
						$dailyotrequest->status_change_by = $detailrequest->account_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
				}
				else if($type=="gm"){

					$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->account_status = 2;
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->gm_change_date;
						$dailyotrequest->status_change_by = $detailrequest->gm_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
					
				}

	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));

	    	//change main status
	    	if($type=="manager"){
				$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["manager_status","!=",1]])->first();
				if(!$detail){
					$monthlyrequest->manager_main_status = 1;
					$monthlyrequest->save();

					//mail to account
					$users = User::permission('change-ot-account-status')->get();
		    		if($users){
		    			foreach($users as $key=>$value){
		    				if($value->noti_type=="email")
		    					Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'manager'));
		    			}
		    		}
				}
			}
			else if($type=="accountant"){
				$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["account_status","!=",1]])->first();
				if(!$detail){
					$monthlyrequest->account_main_status = 1;
					$monthlyrequest->save();

					//mail to gm
					$users = User::permission('change-ot-gm-status')->get();
		    		if($users){
		    			foreach($users as $key=>$value){
		    				if($value->noti_type=="email")
		    					Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'accountant'));
		    			}
		    		}
				}
				else{
					$monthlyrequest->manager_main_status = 0;
					$monthlyrequest->save();

					$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["account_status","=",2]])->get();
					//return mail to manager
					$users = User::permission('change-ot-manager-status')->get();
		    		if($users){
		    			$applicant = User::findOrFail($monthlyrequest->user_id);
		    			foreach($users as $key=>$value){
		    				if($value->noti_type=="email")
		    					Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'accountant'));
		    			}
		    		}
				}
			}
			else if($type=="gm"){
				$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","!=",1]])->first();
				if(!$detail){
					$monthlyrequest->gm_main_status = 1;
					$monthlyrequest->save();

					//calculate ot for attendance table
					$otdetail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",1]])->get();
					foreach($otdetail as $key=>$detailrequest)
					{
						$attendance = Attendance::where([['user_id','=',$detailrequest->user_id],['date','=',$detailrequest->apply_date]])->first();
						if($attendance){

							$ot_hr = getOTHour($detailrequest->id);
							if($detailrequest->ot_type=='Weekday'){
								$attendance->normal_ot_hr += $ot_hr;
							}
							else if($detailrequest->ot_type=='Saturday'){
								$attendance->sat_ot_hr += $ot_hr;
							}
							else if($detailrequest->ot_type=='Sunday'){
								$attendance->sunday_ot_hr += $ot_hr;
							}
							else {
								$attendance->public_holiday_ot_hr += $ot_hr;
							}
							$attendance->ot_request_date = $detailrequest->created_at;
							$attendance->ot_approve_date = $detailrequest->gm_change_date;
							$attendance->ot_approve_by = $detailrequest->gm_change_by;
							$attendance->save();
						}
						else{
							if($detailrequest->end_from_time){
							    $ot_start_time = $detailrequest->end_from_time; 
							    $ot_end_time = $detailrequest->end_to_time;
							    $breaktime = $detailrequest->end_break_hour.":".$detailrequest->end_break_minute;
							}
							else{
							    $ot_start_time = $detailrequest->start_from_time; 
							    $ot_end_time = $detailrequest->start_to_time;
							    $breaktime = $detailrequest->start_break_hour.":".$detailrequest->start_break_minute;
							}

							$ot_hr = getOTHour($detailrequest->id);

							$attendance = new Attendance();
							$attendance->device = "OT";
							$attendance->device_ip = $ip;
							$attendance->device_serial = "OT";
							$attendance->user_id = $detailrequest->user_id;
							$attendance->profile_id = getUserFieldWithId($detailrequest->user_id,'profile_id');
							$attendance->branch_id = getUserFieldWithId($detailrequest->user_id,'branch_id');
							$attendance->date = $detailrequest->apply_date;
							$attendance->start_time = $ot_start_time;
							$attendance->end_time = $ot_end_time;
								$attendance->type = 'OT Type';
							if($detailrequest->ot_type=="Weekday"){
								$attendance->normal_ot_hr = $ot_hr;
								$attendance->type_id = 0;
							}
							else if($detailrequest->ot_type=="Saturday"){
								$attendance->sat_ot_hr = $ot_hr;
									$attendance->type_id = 1;
							}
							else if($detailrequest->ot_type=='Sunday'){
								$attendance->sunday_ot_hr = $ot_hr;
								$attendance->type_id = 2;
							}
							else {
								$attendance->public_holiday_ot_hr = $ot_hr;
								$attendance->type_id = 3;
							}
							$working_start_time = getUserFieldWithId($detailrequest->user_id,'working_start_time');
							$working_end_time = getUserFieldWithId($detailrequest->user_id,'working_end_time');
							$attendance->corrected_start_time = $working_start_time?$working_start_time:$ot_start_time;
							$attendance->corrected_end_time = $working_end_time?$working_end_time:$ot_end_time;

							$attendance->ot_request_date = $detailrequest->created_at;
							$attendance->ot_approve_date = $detailrequest->gm_change_date;
							$attendance->ot_approve_by = $detailrequest->gm_change_by;
							$attendance->created_by = Auth::user()->id;
							$attendance->save();
								
						}

						//update annual ot summary
						$year = Carbon::parse($detailrequest->apply_date)->format('Y');
						$month = Carbon::parse($detailrequest->apply_date)->format('F');
		    			$month = strtolower($month);

		    			if($month=='january' || $month=="february" ||$month=="march")
					        $year = $year-1;

					    $annualotsummary = AnnualOtSummary::where([['user_id','=',$detailrequest->user_id],['year','=',$year]])->first();

					    $ot_hr = getOTHour($detailrequest->id);
					    if($annualotsummary){
					        $annualotsummary->$month = $annualotsummary->$month + $ot_hr;
					        $annualotsummary->save();
					    }
					    else{
					        $annualotsummary = new AnnualOtSummary();
					        $annualotsummary->user_id = $detailrequest->user_id;
					        $annualotsummary->branch = $detailrequest->branch;
					        $annualotsummary->$month = $ot_hr;
					        	$annualotsummary->year = $year;
					        $annualotsummary->save();
					    }
					}

				}
				else{
					$monthlyrequest->manager_main_status = 0;
					$monthlyrequest->account_main_status = 0;
					$monthlyrequest->save();
					
					$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",2]])->get();
					//return mail to manager
					$users = User::permission('change-ot-manager-status')->get();
		    		if($users){
		    			$applicant = User::findOrFail($monthlyrequest->user_id);
		    			foreach($users as $key=>$value){
		    				if($value->noti_type=="email")
		    					Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'gm'));
		    			}
		    		}
		    		$users = User::permission('change-ot-account-status')->get();
		    		if($users){
		    			$applicant = User::findOrFail($monthlyrequest->user_id);
		    			foreach($users as $key=>$value){
		    				if($value->noti_type=="email")
		    					Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'gm'));
		    			}
		    		}
				}

			}	
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	public function changeStatusDeptGM($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyOtRequest::findOrFail($m_id);

			
			$monthlyrequest->manager_reason = $reason;
			$monthlyrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

		    		
					$detailrequest->manager_status = $status;
					$detailrequest->manager_status_reason = $reason;
					$detailrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->manager_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];
					if($status==2){
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->manager_change_date;
						$dailyotrequest->status_change_by = $detailrequest->manager_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
					
	    		}

	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

		    		
					$detailrequest->manager_status = $status;
					$detailrequest->manager_status_reason = $reason;
					$detailrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->manager_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];
					if($status==2){
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->manager_change_date;
						$dailyotrequest->status_change_by = $detailrequest->manager_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
					
	    		}

	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "OT Status:\n";
    			foreach($overtime as $key=>$value){
    				$status_name = $value['status']==1?'Accept':'Reject';
    				$message .= $value['date']."-".$value['ot_type']."-".$status_name;
    				if(count($overtime)!=($key+1)){
    					$message .= "&";
    				}
    			}
    			sendSMS($user->phone,$message);
    		}

	    	//change main status
	    	
			$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["manager_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->manager_main_status = 1;
				$monthlyrequest->save();

				//mail to account
				$users = User::permission('change-ot-account-status')->get();
		    	if($users){
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'manager',"staff"));
		    		}
		    	}
			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->save();
			}
			
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	public function changeStatusAccount($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyOtRequest::findOrFail($m_id);

			$monthlyrequest->account_reason = $reason;
			$monthlyrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

	    		
					$detailrequest->account_status = $status;
					$detailrequest->account_status_reason = $reason;
					$detailrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->account_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->account_change_date;
						$dailyotrequest->status_change_by = $detailrequest->account_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
	    		}
	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

	    		
					$detailrequest->account_status = $status;
					$detailrequest->account_status_reason = $reason;
					$detailrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->account_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->account_change_date;
						$dailyotrequest->status_change_by = $detailrequest->account_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
	    		}
	    			
	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant

	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "OT Status:\n";
    			foreach($overtime as $key=>$value){
    				$status_name = $value['status']==1?'Accept':'Reject';
    				$message .= $value['date']."-".$value['ot_type']."-".$status_name;
    				if(count($overtime)!=($key+1)){
    					$message .= "&";
    				}
    			}
    			sendSMS($user->phone,$message);
    		}

	    	//change main status
	    	
			$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["account_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->account_main_status = 1;
				$monthlyrequest->save();

					//mail to gm
				$users = User::permission('change-ot-gm-status')->get();
		    	if($users){
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new AccountAdminMail($value,$monthlyrequest,Auth::user()->id,'accountant',"staff"));
		    		}
		    	}
			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->account_main_status = 0;
				$monthlyrequest->save();

				$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["account_status","=",2]])->get();
				//return mail to manager

				//$users = User::permission('change-ot-manager-status')->get();
				$terms = explode(',',$user->department_id);
				$user_ids = array();
				foreach($terms as $term){
					$users=User::permission('change-ot-manager-status')->where(function($query) use($term) {
		                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		            	})->pluck("id")->toArray();
					
			        $user_ids = array_merge($user_ids,$users);
				}
				$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
				
		    	if($users){
		    		$applicant = User::findOrFail($monthlyrequest->user_id);
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'accountant',"staff"));
		    		}
		    	}
			}
				
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	public function changeStatusAdminGM($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$status = 1;
			$all_accept_requests = isset($input['all_accept_request'])?$input['all_accept_request']:[];
			$all_reject_requests = isset($input['all_reject_request'])?$input['all_reject_request']:[];
			$overtime = array();
			
			$monthlyrequest = MonthlyOtRequest::findOrFail($m_id);

			$monthlyrequest->gm_reason = $reason;
			$monthlyrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
			$monthlyrequest->save();
			

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_accept_requests)){
	    			$status = 1;
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

	    			$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->account_status = 2;
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->gm_change_date;
						$dailyotrequest->status_change_by = $detailrequest->gm_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
	    		}
	    		if(in_array($id, $all_reject_requests)){
	    			$status = 2;
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

	    			$detailrequest->gm_status = $status;
					$detailrequest->gm_status_reason = $reason;
					$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
					$detailrequest->gm_change_by = Auth::user()->id;

					$detailrequest->save();

					$overtime[] = ["date"=>siteformat_date($detailrequest->apply_date),"ot_type"=>$detailrequest->ot_type,"status"=>$status];

					if($status==2){
						$detailrequest->manager_status = 2;
						$detailrequest->account_status = 2;
						$detailrequest->daily_request = 1;
						$detailrequest->inactive = 1;
						$detailrequest->save();

						$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
						$dailyotrequest->status = 2;
						$dailyotrequest->monthly_status_reason = $reason;
						$dailyotrequest->status_change_date = $detailrequest->gm_change_date;
						$dailyotrequest->status_change_by = $detailrequest->gm_change_by;
						$dailyotrequest->monthly_request = 0;
						$dailyotrequest->monthly_request_id = $detailrequest->id;
						$dailyotrequest->save();

					}
	    		}

	    	}		

	    	//var_dump($overtime);
	    	//send mail to applicant
	    	$user = User::findOrFail($monthlyrequest->user_id);
			if($user->noti_type=="email")
    			Mail::to($user->email)->send(new MonthlyOTStatusMail($user,$overtime,$type,Auth::user()->id));
    		else{
    			$message = "OT Status:\n";
    			foreach($overtime as $key=>$value){
    				$status_name = $value['status']==1?'Accept':'Reject';
    				$message .= $value['date']."-".$value['ot_type']."-".$status_name;
    				if(count($overtime)!=($key+1)){
    					$message .= "&";
    				}
    			}
    			sendSMS($user->phone,$message);
    		}

	    	//change main status
	    	$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","!=",1]])->first();
			if(!$detail){
				$monthlyrequest->gm_main_status = 1;
				$monthlyrequest->save();

				//calculate ot for attendance table
				$otdetail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",1]])->get();
				foreach($otdetail as $key=>$detailrequest)
				{
					$attendance = Attendance::where([['user_id','=',$detailrequest->user_id],['date','=',$detailrequest->apply_date]])->first();
					if($attendance){

						$ot_hr = getOTHour($detailrequest->id);
						if($detailrequest->ot_type=='Weekday'){
							$attendance->normal_ot_hr += $ot_hr;
						}
						else if($detailrequest->ot_type=='Saturday'){
							$attendance->sat_ot_hr += $ot_hr;
						}
						else if($detailrequest->ot_type=='Sunday'){
							$attendance->sunday_ot_hr += $ot_hr;
						}
						else {
							$attendance->public_holiday_ot_hr += $ot_hr;
						}
						$attendance->ot_request_date = $detailrequest->created_at;
						$attendance->ot_approve_date = $detailrequest->gm_change_date;
						$attendance->ot_approve_by = $detailrequest->gm_change_by;
						$attendance->save();
					}
					else{
						if($detailrequest->end_from_time){
							$ot_start_time = $detailrequest->end_from_time; 
							$ot_end_time = $detailrequest->end_to_time;
							$breaktime = $detailrequest->end_break_hour.":".$detailrequest->end_break_minute;
						}
						else{
							$ot_start_time = $detailrequest->start_from_time; 
							$ot_end_time = $detailrequest->start_to_time;
							$breaktime = $detailrequest->start_break_hour.":".$detailrequest->start_break_minute;
						}

						$ot_hr = getOTHour($detailrequest->id);

						$attendance = new Attendance();
						$attendance->device = "OT";
						$attendance->device_ip = $ip;
						$attendance->device_serial = "OT";
						$attendance->user_id = $detailrequest->user_id;
						$attendance->profile_id = getUserFieldWithId($detailrequest->user_id,'profile_id');
						$attendance->branch_id = getUserFieldWithId($detailrequest->user_id,'branch_id');
						$attendance->date = $detailrequest->apply_date;
						$attendance->start_time = $ot_start_time;
						$attendance->end_time = $ot_end_time;
						$attendance->type = 'OT Type';
						if($detailrequest->ot_type=="Weekday"){
							$attendance->normal_ot_hr = $ot_hr;
							$attendance->type_id = 0;
						}
						else if($detailrequest->ot_type=="Saturday"){
							$attendance->sat_ot_hr = $ot_hr;
							$attendance->type_id = 1;
						}
						else if($detailrequest->ot_type=='Sunday'){
							$attendance->sunday_ot_hr = $ot_hr;
							$attendance->type_id = 2;
						}
						else {
							$attendance->public_holiday_ot_hr = $ot_hr;
							$attendance->type_id = 3;
						}
						$working_start_time = getUserFieldWithId($detailrequest->user_id,'working_start_time');
						$working_end_time = getUserFieldWithId($detailrequest->user_id,'working_end_time');
						$attendance->corrected_start_time = $working_start_time?$working_start_time:$ot_start_time;
						$attendance->corrected_end_time = $working_end_time?$working_end_time:$ot_end_time;

						$attendance->ot_request_date = $detailrequest->created_at;
						$attendance->ot_approve_date = $detailrequest->gm_change_date;
						$attendance->ot_approve_by = $detailrequest->gm_change_by;
						$attendance->created_by = Auth::user()->id;
						$attendance->save();
								
					}

					//update annual ot summary
					$year = Carbon::parse($detailrequest->apply_date)->format('Y');
					$month = Carbon::parse($detailrequest->apply_date)->format('F');
		    		$month = strtolower($month);

		    		if($month=='january' || $month=="february" ||$month=="march")
					    $year = $year-1;

					$annualotsummary = AnnualOtSummary::where([['user_id','=',$detailrequest->user_id],['year','=',$year]])->first();

					$ot_hr = getOTHour($detailrequest->id);
					if($annualotsummary){
					    $annualotsummary->$month = $annualotsummary->$month + $ot_hr;
					    $annualotsummary->save();
					}
					else{
					    $annualotsummary = new AnnualOtSummary();
					    $annualotsummary->user_id = $detailrequest->user_id;
					    $annualotsummary->branch = $detailrequest->branch;
					    $annualotsummary->$month = $ot_hr;
					    $annualotsummary->year = $year;
					    $annualotsummary->save();
					}
				}

			}
			else{
				$monthlyrequest->manager_main_status = 0;
				$monthlyrequest->account_main_status = 0;
				$monthlyrequest->gm_main_status = 0;
				$monthlyrequest->save();
					
				$detail = MonthlyOtRequestDetail::where([["monthly_ot_request_id","=",$monthlyrequest->id],["gm_status","=",2]])->get();
				//return mail to manager
				//$users = User::permission('change-ot-manager-status')->get();
				$terms = explode(',',$user->department_id);
				$user_ids = array();
				foreach($terms as $term){
					$users=User::permission('change-ot-manager-status')->where(function($query) use($term) {
		                    $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		            	})->pluck("id")->toArray();
					
			        $user_ids = array_merge($user_ids,$users);
				}
				$users = User::select('users.email','users.employee_name','users.noti_type')->whereIn('id',$user_ids)->get();
				
		    	if($users){
		    		$applicant = User::findOrFail($monthlyrequest->user_id);
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'gm-manager',"staff"));
		    		}
		    	}
		    	$users = User::permission('change-ot-account-status')->get();
		    	if($users){
		    		$applicant = User::findOrFail($monthlyrequest->user_id);
		    		foreach($users as $key=>$value){
		    			if($value->noti_type=="email")
		    				Mail::to($value->email)->send(new ManagerAccountMail($value,$detail,Auth::user()->id,$applicant,'gm-account',"driver"));
		    		}
		    	}
			}

				
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}
	public function changeStatusOld($input,$user,$type,$ip){
		$changestatuses= DB::transaction(function () use ($input,$user,$type,$ip) {

			$reason = $input['reason'];
			$ids = $input['id'];
			$m_id = $input['m_id'];
			$statuses = $input['status'];
			$status_reasons = $input['status_reason'];
			$all_requests = $input['all_request'];
			
			$monthlyrequest = MonthlyOtRequest::findOrFail($m_id);

			if($type=="manager"){
				$monthlyrequest->manager_reason = $reason;
				$monthlyrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
				$monthlyrequest->save();
			}
			else if($type=="accountant"){
				$monthlyrequest->account_reason = $reason;
				$monthlyrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
				$monthlyrequest->save();
			}
			else if($type=="gm"){
				$monthlyrequest->gm_reason = $reason;
				$monthlyrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
				$monthlyrequest->save();
			}

	    	foreach($ids as $key=>$id){

	    		if(in_array($id, $all_requests)){
	    			$detailrequest=MonthlyOtRequestDetail::findOrFail($id);

	    			if($type=="manager"){
						$detailrequest->manager_status = $statuses[$key];
						$detailrequest->manager_status_reason = $status_reasons[$key];
						$detailrequest->manager_change_date = Carbon::now()->format('Y-m-d H:i:s');
						$detailrequest->manager_change_by = Auth::user()->id;

						$detailrequest->save();

						if($statuses[$key]==2){
							$detailrequest->daily_request = 1;
							$detailrequest->inactive = 1;
							$detailrequest->save();

							$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
							$dailyotrequest->status = 2;
							$dailyotrequest->monthly_status_reason = $status_reasons[$key];
							$dailyotrequest->status_change_date = $detailrequest->manager_change_date;
							$dailyotrequest->status_change_by = $detailrequest->manager_change_by;
							$dailyotrequest->monthly_request = 0;
							$dailyotrequest->monthly_request_id = $detailrequest->id;
							$dailyotrequest->save();

						}
					}
					else if($type=="accountant"){
						$detailrequest->account_status = $statuses[$key];
						$detailrequest->account_status_reason = $status_reasons[$key];
						$detailrequest->account_change_date = Carbon::now()->format('Y-m-d H:i:s');
						$detailrequest->account_change_by = Auth::user()->id;

						$detailrequest->save();

						if($statuses[$key]==2){
							$detailrequest->daily_request = 1;
							$detailrequest->inactive = 1;
							$detailrequest->save();

							$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
							$dailyotrequest->status = 2;
							$dailyotrequest->monthly_status_reason = $status_reasons[$key];
							$dailyotrequest->status_change_date = $detailrequest->account_change_date;
							$dailyotrequest->status_change_by = $detailrequest->account_change_by;
							$dailyotrequest->monthly_request = 0;
							$dailyotrequest->monthly_request_id = $detailrequest->id;
							$dailyotrequest->save();

						}
					}
					else if($type=="gm"){

						//check previous status
						if($detailrequest->gm_status==1){
							$attendance = Attendance::where([['user_id','=',$detailrequest->user_id],['date','=',$detailrequest->apply_date]])->first();
							$ot_hr = getOTHour($detailrequest->id);
							if($attendance){

								
								if($detailrequest->ot_type=='Normal'){
									$attendance->normal_ot_hr -= $ot_hr;
								}
								else if($detailrequest->ot_type=='Sunday'){
									$attendance->sunday_ot_hr -= $ot_hr;
								}
								else {
									$attendance->public_holiday_ot_hr -= $ot_hr;
								}
								$attendance->ot_request_date = $detailrequest->created_at;
								$attendance->ot_approve_date = Carbon::now()->format('Y-m-d H:i:s');
								$attendance->ot_approve_by = Auth::user()->id;
								$attendance->save();
							}

							//update annual ot summary
							$year = Carbon::parse($detailrequest->apply_date)->format('Y');
							$month = Carbon::parse($detailrequest->apply_date)->format('F');
		    				$month = strtolower($month);

		    				if($month=='january' || $month=="february" ||$month=="march")
					        		$year = $year-1;

					        $annualotsummary = AnnualOtSummary::where([['user_id','=',$detailrequest->user_id],['year','=',$year]])->first();

					        //$ot_hr = getOTHour($detailrequest->id);

					        if($annualotsummary){
					        	$annualotsummary->$month = $annualotsummary->$month - $ot_hr;
					        	$annualotsummary->save();
					        }
						}

						$detailrequest->gm_status = $statuses[$key];
						$detailrequest->gm_status_reason = $status_reasons[$key];
						$detailrequest->gm_change_date = Carbon::now()->format('Y-m-d H:i:s');
						$detailrequest->gm_change_by = Auth::user()->id;

						$detailrequest->save();

						if($statuses[$key]==2){
							$detailrequest->daily_request = 1;
							$detailrequest->inactive = 1;
							$detailrequest->save();

							$dailyotrequest = DailyOtRequest::findOrFail($detailrequest->daily_ot_request_id);
							$dailyotrequest->status = 2;
							$dailyotrequest->monthly_status_reason = $status_reasons[$key];
							$dailyotrequest->status_change_date = $detailrequest->gm_change_date;
							$dailyotrequest->status_change_by = $detailrequest->gm_change_by;
							$dailyotrequest->monthly_request = 0;
							$dailyotrequest->monthly_request_id = $detailrequest->id;
							$dailyotrequest->save();

						}
						else if($statuses[$key]==1){
							//update attendance
							$attendance = Attendance::where([['user_id','=',$detailrequest->user_id],['date','=',$detailrequest->apply_date]])->first();
							if($attendance){

								$ot_hr = getOTHour($detailrequest->id);
								if($detailrequest->ot_type=='Normal'){
									$attendance->normal_ot_hr += $ot_hr;
								}
								else if($detailrequest->ot_type=='Sunday'){
									$attendance->sunday_ot_hr += $ot_hr;
								}
								else {
									$attendance->public_holiday_ot_hr += $ot_hr;
								}
								$attendance->ot_request_date = $detailrequest->created_at;
								$attendance->ot_approve_date = $detailrequest->gm_change_date;
								$attendance->ot_approve_by = $detailrequest->gm_change_by;
								$attendance->save();
							}
							else{
								if($detailrequest->end_from_time){
							        $ot_start_time = $detailrequest->end_from_time; 
							        $ot_end_time = $detailrequest->end_to_time;
							        $breaktime = $detailrequest->end_break_hour.":".$detailrequest->end_break_minute;
							    }
							    else{
							        $ot_start_time = $detailrequest->start_from_time; 
							        $ot_end_time = $detailrequest->start_to_time;
							        $breaktime = $detailrequest->start_break_hour.":".$detailrequest->start_break_minute;
							    }

							    $ot_hr = getOTHour($detailrequest->id);

								$attendance = new Attendance();
								$attendance->device = "OT";
								$attendance->device_ip = $ip;
								$attendance->device_serial = "OT";
								$attendance->user_id = $detailrequest->user_id;
								$attendance->profile_id = getUserFieldWithId($detailrequest->user_id,'profile_id');
								$attendance->branch_id = getUserFieldWithId($detailrequest->user_id,'branch_id');
								$attendance->date = $detailrequest->apply_date;
								$attendance->start_time = $ot_start_time;
								$attendance->end_time = $ot_end_time;
								$attendance->type = 'OT Type';
								if($detailrequest->ot_type=='Normal'){
									$attendance->normal_ot_hr = $ot_hr;
									$attendance->type_id = 0;
								}
								else if($detailrequest->ot_type=='Sunday'){
									$attendance->sunday_ot_hr = $ot_hr;
									$attendance->type_id = 1;
								}
								else {
									$attendance->public_holiday_ot_hr = $ot_hr;
									$attendance->type_id = 2;
								}
								$working_start_time = getUserFieldWithId($detailrequest->user_id,'working_start_time');
								$working_end_time = getUserFieldWithId($detailrequest->user_id,'working_end_time');
								$attendance->corrected_start_time = $working_start_time?$working_start_time:$ot_start_time;
								$attendance->corrected_end_time = $working_end_time?$working_end_time:$ot_end_time;

								$attendance->ot_request_date = $detailrequest->created_at;
								$attendance->ot_approve_date = $detailrequest->gm_change_date;
								$attendance->ot_approve_by = $detailrequest->gm_change_by;
								$attendance->created_by = Auth::user()->id;
								$attendance->save();
								
							}

							//update annual ot summary
							$year = Carbon::parse($detailrequest->apply_date)->format('Y');
							$month = Carbon::parse($detailrequest->apply_date)->format('F');
		    				$month = strtolower($month);

		    				if($month=='january' || $month=="february" ||$month=="march")
					        		$year = $year-1;

					        $annualotsummary = AnnualOtSummary::where([['user_id','=',$detailrequest->user_id],['year','=',$year]])->first();

					        $ot_hr = getOTHour($detailrequest->id);

					        if($annualotsummary){
					        	$annualotsummary->$month = $annualotsummary->$month + $ot_hr;
					        	$annualotsummary->save();
					        }
					        else{
					        	$annualotsummary = new AnnualOtSummary();
					        	$annualotsummary->user_id = $detailrequest->user_id;
					        	$annualotsummary->branch = $detailrequest->branch;
					        	$annualotsummary->$month = $ot_hr;
					        	$annualotsummary->year = $year;
					        	$annualotsummary->save();
					        }


						}
					}

	    		}
	    		

	    	}			
							
			return $monthlyrequest;

		});

		return $changestatuses;
		
	}

	public function annualOTSummary($name,$order,$from_date=null,$to_date=null,$employee=null,$n){

		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;

		if(Auth::user()->can('ot-read-all')){
			$query = User::select('users.employee_name','users.department_id','annual_ot_summaries.*',DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"));
		}
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			$query = User::select('users.employee_name','users.department_id','annual_ot_summaries.*',DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->whereIn("user_id",$user_ids);
		}
		else{
			$query = User::select('users.employee_name','users.department_id','annual_ot_summaries.*',DB::raw("(select $month from ns_salaries where users.id = ns_salaries.user_id and year=$year) AS salary"))->where("user_id","=",Auth::user());
		}
		
		$query->leftJoin('annual_ot_summaries','users.id','=','annual_ot_summaries.user_id');

		$query->orderBy("salary","desc");

		if($from_date!=null){
	        $query->where('year','>=',$from_date);
	    }
	    if($to_date!=null){
	        $query->where('year','<=',$to_date);
	    }
	    if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('annual_ot_summaries.user_id','=',$employee);
			});
		}
		$query->orderBy("annual_ot_summaries.user_id","asc");
		//return $query->get();
		$result = $query->orderBy('branch')->get()->groupBy(function($item) {
            return $item->branch;
        });

        return $result;
			
	}

	public function indexMonthly($name,$order){
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		if(Auth::user()->can('ot-read-all'))
			return MonthlyOtRequest::select('monthly_ot_request_details.*','monthly_ot_requests.gm_reason','users.employee_name',DB::raw("(select $month from ns_salaries where monthly_ot_request_details.user_id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("monthly_ot_request_details.user_id","asc");
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			return MonthlyOtRequest::select('monthly_ot_request_details.*','monthly_ot_requests.gm_reason','users.employee_name',DB::raw("(select $month from ns_salaries where monthly_ot_request_details.user_id = ns_salaries.user_id and year=$year) AS salary"))->where('monthly_ot_request_details.manager_status','=',1)->whereIn('monthly_ot_request_details.user_id',$user_ids)->orderBy("salary","desc")->orderBy("monthly_ot_request_details.user_id","asc");
		}
		
		else
			return MonthlyOtRequest::select('monthly_ot_request_details.*','monthly_ot_requests.gm_reason','users.employee_name',DB::raw("(select $month from ns_salaries where monthly_ot_request_details.user_id = ns_salaries.user_id and year=$year) AS salary"))->where('monthly_ot_request_details.user_id','=',Auth::user()->id)->orderBy("salary","desc")->orderBy("monthly_ot_request_details.user_id","asc");
		
		
	}
	public function indexMonthlyDriver($name,$order){
		$month = strtolower(Carbon::now()->format('F'));
		$year = Carbon::now()->format("Y");
		if($month=="january" or $month=="february" or $month=="march")
			$year = $year - 1;
		if(Auth::user()->can('ot-read-all'))
			return MonthlyDriverOtRequest::select('monthly_driver_ot_request_details.*','monthly_driver_ot_requests.gm_reason','users.employee_name',DB::raw("(select $month from ns_salaries where monthly_driver_ot_requests.user_id = ns_salaries.user_id and year=$year) AS salary"))->orderBy("salary","desc")->orderBy("monthly_driver_ot_request_details.user_id","asc");
		else if(Auth::user()->can('ot-read-group')){
			//$user_ids = User::where('department_id','=',Auth::user()->department_id)->pluck('id');
			$terms = explode(',',auth()->user()->department_id);
		    $user_ids = array();
		    foreach($terms as $term){
		        $users=User::where(function($query) use($term) {
		                $query->whereRaw("find_in_set('".$term."',users.department_id)");
		                    
		        })->pluck("id")->toArray();
		        $user_ids = array_merge($user_ids,$users);
		    }
			return MonthlyDriverOtRequest::select('monthly_driver_ot_request_details.*','monthly_driver_ot_requests.gm_reason','users.employee_name',DB::raw("(select $month from ns_salaries where monthly_driver_ot_requests.user_id = ns_salaries.user_id and year=$year) AS salary"))->where('monthly_driver_ot_request_details.manager_status','=',1)->whereIn('monthly_driver_ot_request_details.user_id',$user_ids)->orderBy("salary","desc")->orderBy("monthly_driver_ot_request_details.user_id","asc");
		}
		
		else
			return MonthlyDriverOtRequest::select('monthly_driver_ot_request_details.*','monthly_driver_ot_requests.gm_reason','users.employee_name',DB::raw("(select $month from ns_salaries where monthly_driver_ot_requests.user_id = ns_salaries.user_id and year=$year) AS salary"))->where('monthly_driver_ot_request_details.user_id','=',Auth::user()->id)->orderBy("salary","desc")->orderBy("monthly_driver_ot_request_details.user_id","asc");
		
		
		
	}

	public function monthlyOTSummary($name,$order,$from_date=null,$to_date=null,$employee=null,$employee_type=null,$n){
		$query=$this->indexMonthly("users.branch_id","asc");
		$query1=$this->indexMonthlyDriver("users.branch_id","asc");

		//$query->orderBy("users.id","asc");
		//$query1->orderBy("users.id","asc");

		//$query->where('daily_request','=',0);
		$query->where('manager_main_status','=',1);
		$query->where('account_main_status','=',1);
		$query->where('gm_main_status','=',1);
		
		$query1->where('manager_main_status','=',1);
		$query1->where('account_main_status','=',1);
		$query1->where('gm_main_status','=',1);

		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_request_details.user_id');

		$query1->leftJoin('monthly_driver_ot_request_details','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query1->leftJoin('users','users.id','=','monthly_driver_ot_request_details.user_id');

		$from_date = "01/".$from_date;
		if($from_date!=null){
			//$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_request_details.apply_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
            $query1->whereRaw( " CAST(monthly_driver_ot_request_details.apply_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_request_details.apply_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
            $query1->whereRaw( " CAST(monthly_driver_ot_request_details.apply_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_request_details.user_id','=',$employee);
			});

			$query1->where(function($q) use ($employee){
				$q->where('monthly_driver_ot_request_details.user_id','=',$employee);
			});
		}
		if($employee_type!=null){
			$query->where(function($q) use ($employee_type){
				$q->where('users.employee_type_id','=',$employee_type);
			});
			$query1->where(function($q) use ($employee_type){
				$q->where('users.employee_type_id','=',$employee_type);
			});
		}
		
		//$query->groupBy('monthly_ot_request_details.id');
		
        $result = $query->get();
        $result1 = $query1->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->user_id]['name'] = $value->employee_name;
			$request_array[$value->user_id]['emp_type'] = "staff";
			if(!isset($request_array[$value->user_id]['remark']))
				$request_array[$value->user_id]['remark'] = $value->gm_reason;
			$ot_hr = getOTHour($value->id);
			if($value->ot_type=="Weekday"){
				
				$weekday = explode(":",convertTime($ot_hr));
                if(isset($request_array[$value->user_id]['weekday_hour']))
					$request_array[$value->user_id]['weekday_hour'] += $weekday[0];
				else
					$request_array[$value->user_id]['weekday_hour'] = $weekday[0];

				if(isset($request_array[$value->user_id]['weekday_minute']))
					$request_array[$value->user_id]['weekday_minute'] += $weekday[1];
				else
					$request_array[$value->user_id]['weekday_minute'] = $weekday[1];
			}
			else{
				$holiday = explode(":",convertTime($ot_hr));
                if(isset($request_array[$value->user_id]['holiday_hour']))
					$request_array[$value->user_id]['holiday_hour'] += $holiday[0];
				else
					$request_array[$value->user_id]['holiday_hour'] = $holiday[0];

				if(isset($request_array[$value->user_id]['holiday_minute']))
					$request_array[$value->user_id]['holiday_minute'] += $holiday[1];
				else
					$request_array[$value->user_id]['holiday_minute'] = $holiday[1];
			}

			
			if(!isset($request_array[$value->user_id]['ot_payment'])){
				$ot_payment = getOTPayment($value->user_id,$from_date);
				
				$request_array[$value->user_id]['ot_payment'] = $ot_payment;
			}
			

			if(isDriver($value->user_id) or isAssistant($value->user_id)){
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = getTaxiCharge($value->user_id,$from_date);
				}
			}
			else{
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = 0;
				}
			}
		}
		foreach ($result1 as $key => $value) {

			$request_array[$value->user_id]['name'] = $value->employee_name;
			$request_array[$value->user_id]['emp_type'] = "driver";
			if(!isset($request_array[$value->user_id]['remark']))
				$request_array[$value->user_id]['remark'] = $value->gm_reason;
			$ot_hr = getOTHour($value->id,'driver');
			if($value->ot_type=="Weekday"){
				
				$weekday = explode(":",convertTime($ot_hr));
                if(isset($request_array[$value->user_id]['weekday_hour']))
					$request_array[$value->user_id]['weekday_hour'] += $weekday[0];
				else
					$request_array[$value->user_id]['weekday_hour'] = $weekday[0];

				if(isset($request_array[$value->user_id]['weekday_minute']))
					$request_array[$value->user_id]['weekday_minute'] += $weekday[1];
				else
					$request_array[$value->user_id]['weekday_minute'] = $weekday[1];
			}
			else{
				$holiday = explode(":",convertTime($ot_hr));
                if(isset($request_array[$value->user_id]['holiday_hour']))
					$request_array[$value->user_id]['holiday_hour'] += $holiday[0];
				else
					$request_array[$value->user_id]['holiday_hour'] = $holiday[0];

				if(isset($request_array[$value->user_id]['holiday_minute']))
					$request_array[$value->user_id]['holiday_minute'] += $holiday[1];
				else
					$request_array[$value->user_id]['holiday_minute'] = $holiday[1];
			}

			
			if(!isset($request_array[$value->user_id]['ot_payment'])){
				$ot_payment = getOTPayment($value->user_id,$from_date);
				
				$request_array[$value->user_id]['ot_payment'] = $ot_payment;
			}
			

			if(isDriver($value->user_id) or isAssistant($value->user_id)){
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = getTaxiCharge($value->user_id,$from_date);
				}
			}
			else{
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = 0;
				}
			}
		}
		return $request_array;


	}

	public function monthlyOTDetail($name,$order,$from_date=null,$to_date=null,$employee=null,$employee_type=null,$n){
		$query=$this->indexMonthly('users.branch_id',"asc");
		$query1=$this->indexMonthlyDriver('users.branch_id',"asc");
		//$query->orderBy("users.id","asc");
		//$query1->orderBy("users.id","asc");

		//$query->where('daily_request','=',0);
		$query->where('manager_main_status','=',1);
		$query->where('account_main_status','=',1);
		$query->where('gm_main_status','=',1);
		
		$query1->where('manager_main_status','=',1);
		$query1->where('account_main_status','=',1);
		$query1->where('gm_main_status','=',1);

		$query->leftJoin('monthly_ot_request_details','monthly_ot_requests.id','=','monthly_ot_request_details.monthly_ot_request_id');
		$query->leftJoin('users','users.id','=','monthly_ot_request_details.user_id');

		$query1->leftJoin('monthly_driver_ot_request_details','monthly_driver_ot_requests.id','=','monthly_driver_ot_request_details.monthly_ot_request_id');
		$query1->leftJoin('users','users.id','=','monthly_driver_ot_request_details.user_id');

		$from_date = "01/".$from_date;
		if($from_date!=null){
			//$from_date = "01/".$from_date;
            $query->whereRaw( " CAST(monthly_ot_request_details.apply_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
            $query1->whereRaw( " CAST(monthly_driver_ot_request_details.apply_date AS DATE) >= STR_TO_DATE(\"$from_date\", '%d/%m/%Y')");
        }
        if($to_date!=null){
        	$to_date = "31/".$to_date;
            $query->whereRaw( " CAST(monthly_ot_request_details.apply_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
            $query1->whereRaw( " CAST(monthly_driver_ot_request_details.apply_date AS DATE) <= STR_TO_DATE(\"$to_date\", '%d/%m/%Y')");
        }
        if($employee!=null){
			$query->where(function($q) use ($employee){
				$q->where('monthly_ot_request_details.user_id','=',$employee);
			});
			$query1->where(function($q) use ($employee){
				$q->where('monthly_driver_ot_request_details.user_id','=',$employee);
			});
		}
		if($employee_type!=null){
			$query->where(function($q) use ($employee_type){
				$q->where('users.employee_type_id','=',$employee_type);
			});
			$query1->where(function($q) use ($employee_type){
				$q->where('users.employee_type_id','=',$employee_type);
			});
		}
		

		//$query->groupBy('monthly_ot_request_details.id');
		
        $result = $query->get();
        $result1 = $query1->get();
        //return $result;
		$request_array = array();

		foreach ($result as $key => $value) {
			$request_array[$value->user_id]['name'] = $value->employee_name;
			$request_array[$value->user_id]['emp_type'] = "staff";
			if(!isset($request_array[$value->user_id]['remark']))
				$request_array[$value->user_id]['remark'] = $value->gm_reason;
			$ot_hr = getOTHour($value->id);

			if($value->end_from_time){
                $start_time = siteformat_time24($value->end_from_time);
                $end_time = siteformat_time24($value->end_to_time);
                $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->end_reason;
                $next_day = $value->end_next_day;
            }
            else{
                $start_time = siteformat_time24($value->start_from_time);
                $end_time = siteformat_time24($value->start_to_time);
                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->start_reason;
                $next_day = $value->start_next_day;
            }
                         
            if($value->ot_type=="Weekday" or $value->ot_type=="Saturday"){
            	$monday = explode(":",convertTime($ot_hr));
                if(isset($request_array[$value->user_id]['monday_hour']))
					$request_array[$value->user_id]['monday_hour'] += $monday[0];
				else
					$request_array[$value->user_id]['monday_hour'] = $monday[0];

				if(isset($request_array[$value->user_id]['monday_minute']))
					$request_array[$value->user_id]['monday_minute'] += $monday[1];
				else
					$request_array[$value->user_id]['monday_minute'] = $monday[1];
				
            }
            else if($value->ot_type=="Sunday"){
                if (strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and !$next_day){
                    
					$ot_hr_min = explode(":",convertTime($ot_hr));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and $next_day){
                	$time = getTimeDiff($start_time,"8:00")+getTimeDiff("18:00","24:00") + getTimeDiff("00:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff("8:00","18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and !$next_day){
                	$time = getTimeDiff($start_time,"8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and $next_day){
                	$time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and !$next_day){
                	$time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00","18:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and $next_day){
                	$time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00","18:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                //>18
                else if (strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                    
					$ot_hr_min = explode(":",convertTime($ot_hr));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                	$time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff("8:00","18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					

                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) < strtotime("8:00")){
                	$time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("8:00") and strtotime($end_time) < strtotime("18:00")){
                	$time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff("8:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					

                }

                //between
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and !$next_day){
                    $ot_hr_min = explode(":",convertTime($ot_hr));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and $next_day){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00") + getTimeDiff("8:00",$end_time);
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                    $time = getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00")+getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00")+getTimeDiff("8:00","18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <= strtotime("8:00")){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00",$end_time);
                	if(isset($request_array[$value->user_id]['sunday_less']))
						$request_array[$value->user_id]['sunday_less'] += $time;
					else
						$request_array[$value->user_id]['sunday_less'] = $time;$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                
            }
            else{
                
				$ot_hr_min = explode(":",convertTime($ot_hr));
	            if(isset($request_array[$value->user_id]['public_hour']))
					$request_array[$value->user_id]['public_hour'] += $ot_hr_min[0];
				else
					$request_array[$value->user_id]['public_hour'] = $ot_hr_min[0];

				if(isset($request_array[$value->user_id]['public_minute']))
					$request_array[$value->user_id]['public_minute'] += $ot_hr_min[1];
				else
					$request_array[$value->user_id]['public_minute'] = $ot_hr_min[1];
					
            }
			
			if(!isset($request_array[$value->user_id]['ot_payment'])){
				$ot_payment = getOTPayment($value->user_id,$from_date);
				
				$request_array[$value->user_id]['ot_payment'] = $ot_payment;
			}
			

			if(isDriver($value->user_id) or isAssistant($value->user_id)){
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = getTaxiCharge($value->user_id,$from_date);
				}
			}
			else{
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = 0;
				}
			}
		}
		foreach ($result1 as $key => $value) {
			$request_array[$value->user_id]['name'] = $value->employee_name;
			$request_array[$value->user_id]['emp_type'] = "driver";
			if(!isset($request_array[$value->user_id]['remark']))
				$request_array[$value->user_id]['remark'] = $value->gm_reason;
			$ot_hr = getOTHour($value->id,"driver");

			if($value->end_from_time){
                $start_time = siteformat_time24($value->end_from_time);
                $end_time = siteformat_time24($value->end_to_time);
                $break_hour = $value->end_break_hour?$value->end_break_hour.' hr':'';
                $break_min = $value->end_break_minute?$value->end_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->end_reason;
                $next_day = $value->end_next_day;
            }
            else{
                $start_time = siteformat_time24($value->start_from_time);
                $end_time = siteformat_time24($value->start_to_time);
                $break_hour = $value->start_break_hour?$value->start_break_hour.' hr':'';
                $break_min = $value->start_break_minute?$value->start_break_minute.' min':'';
                $break_time = $break_hour.' '.$break_min;
                $reason = $value->start_reason;
                $next_day = $value->start_next_day;
            }
                         
            if($value->ot_type=="Weekday" or $value->ot_type=="Saturday"){
            	$monday = explode(":",convertTime($ot_hr));
                if(isset($request_array[$value->user_id]['monday_hour']))
					$request_array[$value->user_id]['monday_hour'] += $monday[0];
				else
					$request_array[$value->user_id]['monday_hour'] = $monday[0];

				if(isset($request_array[$value->user_id]['monday_minute']))
					$request_array[$value->user_id]['monday_minute'] += $monday[1];
				else
					$request_array[$value->user_id]['monday_minute'] = $monday[1];
				
            }
            else if($value->ot_type=="Sunday"){
                if (strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and !$next_day){
                    
					$ot_hr_min = explode(":",convertTime($ot_hr));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) < strtotime("8:00") and $next_day){
                	$time = getTimeDiff($start_time,"8:00")+getTimeDiff("18:00","24:00") + getTimeDiff("00:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff("8:00","18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and !$next_day){
                	$time = getTimeDiff($start_time,"8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("8:00") and strtotime($end_time) < strtotime("18:00") and $next_day){
                	$time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and !$next_day){
                	$time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00","18:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) < strtotime("8:00") and strtotime($end_time) > strtotime("18:00") and $next_day){
                	$time = getTimeDiff($start_time,"8:00") + getTimeDiff("18:00","24:00") + getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
					$time = getTimeDiff("8:00","18:00") + getTimeDiff("8:00","18:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                //>18
                else if (strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                    
					$ot_hr_min = explode(":",convertTime($ot_hr));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					
                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                	$time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00") + getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff("8:00","18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					

                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) < strtotime("8:00")){
                	$time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

                }
                else if(strtotime($start_time) > strtotime("18:00") and strtotime($end_time) >strtotime("8:00") and strtotime($end_time) < strtotime("18:00")){
                	$time = getTimeDiff($start_time,"24:00")+getTimeDiff("00:00","8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff("8:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					

                }

                //between
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and !$next_day){
                    $ot_hr_min = explode(":",convertTime($ot_hr));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <=strtotime("18:00") and $next_day){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00");
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00") + getTimeDiff("8:00",$end_time);
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and !$next_day){
                    $time = getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) >strtotime("18:00") and $next_day){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00","8:00")+getTimeDiff("18:00",$end_time);
                	$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00")+getTimeDiff("8:00","18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                else if (strtotime($start_time) >= strtotime("8:00") and strtotime($end_time) <= strtotime("8:00")){
                    $time = getTimeDiff("18:00","24:00")+getTimeDiff("00:00",$end_time);
                	if(isset($request_array[$value->user_id]['sunday_less']))
						$request_array[$value->user_id]['sunday_less'] += $time;
					else
						$request_array[$value->user_id]['sunday_less'] = $time;$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_less_hour']))
						$request_array[$value->user_id]['sunday_less_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_less_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_less_minute']))
						$request_array[$value->user_id]['sunday_less_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_less_minute'] = $ot_hr_min[1];
					

					$time = getTimeDiff($start_time,"18:00");
					$ot_hr_min = explode(":",convertTime($time));
	                if(isset($request_array[$value->user_id]['sunday_between_hour']))
						$request_array[$value->user_id]['sunday_between_hour'] += $ot_hr_min[0];
					else
						$request_array[$value->user_id]['sunday_between_hour'] = $ot_hr_min[0];

					if(isset($request_array[$value->user_id]['sunday_between_minute']))
						$request_array[$value->user_id]['sunday_between_minute'] += $ot_hr_min[1];
					else
						$request_array[$value->user_id]['sunday_between_minute'] = $ot_hr_min[1];
					
                }
                
            }
            else{
                
				$ot_hr_min = explode(":",convertTime($ot_hr));
	            if(isset($request_array[$value->user_id]['public_hour']))
					$request_array[$value->user_id]['public_hour'] += $ot_hr_min[0];
				else
					$request_array[$value->user_id]['public_hour'] = $ot_hr_min[0];

				if(isset($request_array[$value->user_id]['public_minute']))
					$request_array[$value->user_id]['public_minute'] += $ot_hr_min[1];
				else
					$request_array[$value->user_id]['public_minute'] = $ot_hr_min[1];
					
            }
			
			if(!isset($request_array[$value->user_id]['ot_payment'])){
				$ot_payment = getOTPayment($value->user_id,$from_date);
				
				$request_array[$value->user_id]['ot_payment'] = $ot_payment;
			}
			

			if(isDriver($value->user_id) or isAssistant($value->user_id)){
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = getTaxiCharge($value->user_id,$from_date);
				}
			}
			else{
				if(!isset($request_array[$value->user_id]['taxi_charge'])){
					
					$request_array[$value->user_id]['taxi_charge'] = 0;
				}
			}
		}
		return $request_array;


	}

	public function monthlyOTIndividual($user_id,$date,$type){
		
		$query=MonthlyOtRequestDetail::select("*");
		//$query->where('daily_request','=',0);
		
		$query1=MonthlyDriverOtRequestDetail::select("*");
		
		$query->where('gm_status','=',1);
		$query->where('user_id','=',$user_id);

		$query1->where('gm_status','=',1);
		$query1->where('user_id','=',$user_id);

		$query->where("apply_date",'like',"$date%");
		$query->orderBy('apply_date','asc');

		$query1->where("apply_date",'like',"$date%");
		$query1->orderBy('apply_date','asc');
		$result = array();
		if(count($query->get())){
			foreach($query->get() as $key=>$value)
        	$result[] = $value;
		}
        if(count($query1->get())){
			foreach($query1->get() as $key=>$value)
        	$result[] = $value;
		}
        return $result;


	}

	
}
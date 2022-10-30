<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Kyslik\ColumnSortable\Sortable;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class Enrollment extends Model
{
    use HasFactory, HasRoles, Sortable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    protected $fillable = [
        'user_id',
        'course_id',
        'transaction_id',
        'name',
        'duration',
        'amount',
        'status',
        'expiry_date',
        'total_marks'    
    ];
    
    /**
    * The attributes that are sortable.
    *
    * @var array
    */

    public $sortable = [
        'id',
        'name',
        'duration',
        'amount',
        'status',
        'created_at',
        'updated_at'
    ]; 
    
    protected $casts = [ 
        'id' => 'string',    
        'status' => 'string',
        'course_id' => 'string',
        'user_id' => 'string',
        'topic_id' => 'string',
        'enrollment_id' => 'string',
        'duration' => 'string',
        'certification' => 'string',
        'total_marks' => 'string'
    ];


    public function scopeCreatedAt(Builder $query, $date): Builder
    { 
        return $query->whereDate('created_at', '=', Carbon::parse($date));
    }  

    /**
    * Scope a query to only include active enrollments.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @return \Illuminate\Database\Eloquent\Builder
    */

    public function scopeActive($query)
    {
        $now = Carbon::now();
        return $query->where('status', 1)->whereDate('expiry_date','>',$now);
    }

    /**
    * Generate certifigate by embedding user's info.
    *   
    */

    public function generateCertificate($userdata){
        //print_r($userdata); exit;
        $certificate_template_file = Storage::disk('local')->path('bss/certificate.png');
        $font_path = Storage::disk('local')->path('bss/font.ttf');


        // Create Image From Existing File
        $png_image = imagecreatefrompng($certificate_template_file);
        //$png_image=imagecreatetruecolor(100,100);
        
        // Allocate A Color For The Text
        $black = imagecolorallocate($png_image, 25, 11, 51);        
            
        // Set Text to Be Printed On Image
        $regNo = 'TN'.str_pad($userdata->user_id,5,"0",STR_PAD_LEFT);
        $today = date('d-m-Y');        
        $currentYear = date("Y");
        $grade = $userdata->grade;
        $topic_name = $userdata->topic_name;
        $user_name = 'Mr/Mrs/Miss. ' . ucwords($userdata->user_name);

        imagettftext($png_image, 20, 0, 230, 75, $black, $font_path, $regNo);
        imagettftext($png_image, 20, 0, 620, 75, $black, $font_path, $today);    
        imagettftext($png_image, 20, 0, 250, 545, $black, $font_path, $user_name);
        imagettftext($png_image, 20, 0, 45, 635, $black, $font_path, $userdata->course_name);
        imagettftext($png_image, 20, 0, 500, 685, $black, $font_path, $currentYear);
        imagettftext($png_image, 20, 0, 45, 725, $black, $font_path, $topic_name);
        imagettftext($png_image, 20, 0, 45, 775, $black, $font_path, '');
        imagettftext($png_image, 20, 0, 250, 822, $black, $font_path, $grade);
        imagettftext($png_image, 20, 0, 90, 870, $black, $font_path, $grade);
        imagettftext($png_image, 20, 0, 45, 915, $black, $font_path, $grade);
    
        // Send Image to Browser 
        $certificate_file_name = $userdata->course_id.'-'.date('mdYHis').'-'.uniqid().'.png';
        $file_path = 'bss/certificates/'.$userdata->user_id;
        $certifcate_path = Storage::disk('local')->path($file_path);
        File::isDirectory($certifcate_path) or File::makeDirectory($certifcate_path, 0755, true, true);
        $file_name = $userdata->course_id.'-'.date('mdYHis').'-'.uniqid().'.png';
        $certificate_file = $certifcate_path . '/' . $file_name;
        imagepng($png_image, $certificate_file);

        //header('Content-type: image/png'); 
        //imagepng($png_image);
    
        // Clear Memory
        imagedestroy($png_image);

        return $file_path.'/'.$file_name;        
    }

    public function getGrade($score){
        $result = '';
        switch($score){
            case $score < 8:
                $result = 'FAIL';
                break;
            case $score >= 10 && $score < 15:
                $result = 'SECOND CLASS';
                break;
            case $score >= 15 && $score <= 20:
                $result = 'FIRST CLASS';
            break;
            default:
                $result = 'THIRD CLASS';
        }
        return $result;
    }
}

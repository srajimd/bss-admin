<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Question;
use App\Models\Answer;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithStartRow;

class QuestionsImport implements ToCollection, WithStartRow
{
    private $headings;

    public function __construct(int $headings) 
    {
        $this->headings = $headings;
    }

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {

        foreach ($collection as $row) 
        {               

            $question = Question::updateOrCreate(
                [
                    'id' => (int)$row[0]
                ],
                [
                    'course_id' => (int)$row[1],
                    'name' => $row[2],
                    'status' => (int)$row[3]
                ]
            );       

            Answer::where('question_id', $question->id)->delete(); 

            if($this->headings > 4){
                for($i=4; $i<$this->headings; $i++){
                    if($row[$i] != ''){
                        if($i == '4'){
                            $correct_answer = 1; 
                        }else{
                            $correct_answer = 0; 
                        }
                                                
                        $answer = Answer::create([
                            'question_id' => $question->id,
                            'correct_answer' => $correct_answer,
                            'name' => $row[$i],                
                        ]);
                    }                    
                }
            }            
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}

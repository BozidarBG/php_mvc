<?php

namespace App\core;

trait RelationsTrait
{

    //returns arrayOfMainObjects with new property that has array of secondClassObject related from pivot
    //arrOfCourses, CoursesTeachers::class, Teacher::class, ->teacher, 'course_id, 'teacher_id'
    public function belongsToMany(array $arrayOfMainObjects, $pivotTableClass, $secondClass, string $newProperty,
                                  string $mainIdentifier, string $secondIdentifier){

        if(count($arrayOfMainObjects)===0){
            return [];
        }
        $pivotModel=new $pivotTableClass();
        $secondClassModel=new $secondClass();//$teacherModel

        $mainObjectIds=$this->getIds($arrayOfMainObjects, 'id'); //take all id's
        //vardump($mainObjectIds);
        //we need from pivot table all teachers for courses ids.
        $pivotResult=$pivotModel->select([$mainIdentifier, $secondIdentifier])->whereIn($mainIdentifier, $mainObjectIds)->all();
        //vardump($pivotResult); //[{"course_id":1, "teacher_id":1}, {"course_id":2, "teacher_id":2},
        // {"course_id":1, "teacher_id":3},{"course_id":2, "teacher_id":3}
        //some teachers might repeat
        $secondClassIdsArr=$this->getIds($pivotResult, $secondIdentifier);
        //vardump($secondObjects_id_arr); //[1,2,3]
        //take all teachers from DB
        $secondClassObjectsArray=$secondClassModel->select([])->whereIn('id', $secondClassIdsArr)->all();
        //vardump($secondClassObjectsArray;
        $newArrayOfMainObjects=[];
        foreach($arrayOfMainObjects as $mainObject){
            $secondObjectsForMainObject=[];
            $secondObjectsIds=[];
            foreach($pivotResult as $obj){
                if($obj->$mainIdentifier===$mainObject->id){
                    $secondObjectsIds[]=$obj->$secondIdentifier;
                }
            }
            //vardump($secondObjectsIds);
            //we found teachers_ids for this course. lets get teachers info for it
            foreach($secondObjectsIds as $id){
                $filtered=array_filter($secondClassObjectsArray, function($secObj) use($id){
                    return $id===$secObj->id;
                });
                $secondObjectsForMainObject[]=array_pop($filtered);
            }
            $filtered=null;
            //vardump($secondObjectsForMainObject);
            $newMainObject=new \stdClass();
            foreach($mainObject as $prop=>$value){
                $newMainObject->{$prop}=$value;
            }
            $newMainObject->{$newProperty}=$secondObjectsForMainObject;
            $newArrayOfMainObjects[]=$newMainObject;
        }//end foreach cousres as course
        return $newArrayOfMainObjects;
    }

    public function getIds(array $arrOfObjects, $identifier){
        $ids=[];
        foreach($arrOfObjects as $obj){
            $ids[]=isset($obj->$identifier) ? $obj->$identifier : null;
        }
        array_filter($ids, function($el){
            return $el !== null;
        });
        return array_unique($ids);
    }

    //returns arrayOfMainObjects with new property that has array of secondClassObject
    //object Post, User::class, ->user, 'user_id, we need to find user with id === post->user_id
    public function belongsTo2(array $arrayOfMainObjects, $secondClass, string $newProperty,
                              string $mainIdentifier){
        $secondClassModel=new $secondClass();

        $newArrayOfMainObjects=[];
        foreach($arrayOfMainObjects as $mainObject){
            $secondClassObjectId=$mainObject->$mainIdentifier;//post->user_id = 2
            $secondObject=$secondClassModel->select([])->where(['id', $secondClassObjectId])->limit(1)->get('first');
            //vardump($secondObject);
            $newMainObject=new \stdClass();
            foreach($mainObject as $prop=>$value){
                $newMainObject->{$prop}=$value;
            }
            $newMainObject->{$newProperty}=$secondObject;
            $newArrayOfMainObjects[]=$newMainObject;
        }

        return $newArrayOfMainObjects;
    }

    //returns arrayOfMainObjects with new property that has array of secondClassObject
    //object Post, User::class, ->user, 'user_id, we need to find user with id === post->user_id
    public function belongsTo(array $arrayOfMainObjects, $secondClass, string $newProperty,
                              string $mainIdentifier){

        if(count($arrayOfMainObjects)===0){
            return [];
        }
        $arrayOfSecondClassIds=$this->getIds($arrayOfMainObjects, $mainIdentifier);
        $secondClassModel=new $secondClass();
        $arrayOfSecondClassModelObjects=$secondClassModel->select([])->whereIn('id', $arrayOfSecondClassIds)->all();
        //vardump($arrayOfSecondClassIds);
        $newArrayOfMainObjects=[];

        foreach($arrayOfMainObjects as $mainObject){
            $secondClassObjectId=$mainObject->$mainIdentifier;//post->user_id = 2
            //$secondObject=$secondClassModel->select([])->where(['id', $secondClassObjectId])->limit(1)->get('first');
            //vardump($secondObject);
            $filtered=array_filter($arrayOfSecondClassModelObjects, function($secObj) use($mainObject, $mainIdentifier){
                return $mainObject->$mainIdentifier===$secObj->id;
            });
            $newMainObject=new \stdClass();
            foreach($mainObject as $prop=>$value){
                $newMainObject->{$prop}=$value;
            }
            $newMainObject->{$newProperty}=array_pop($filtered);
            $newArrayOfMainObjects[]=$newMainObject;
        }

        return $newArrayOfMainObjects;
    }

    //returns arrayOfMainObjects with new property that has array of secondClassObject
    //arrOfUsers, Post::class, ->posts, 'user_id,
    public function hasMany(array $arrayOfMainObjects, $secondClass, string $newProperty,
                              string $mainIdentifier){
        if(count($arrayOfMainObjects)===0){
            return [];
        }
        $secondClassModel=new $secondClass();
        //vardump($arrayOfMainObjects);
        //extract ids
        $arrayOfMainObjectsIds=$this->getIds($arrayOfMainObjects, 'id');
        $arrayOfSecondClassModelObjects=$secondClassModel->select([])->whereIn($mainIdentifier, $arrayOfMainObjectsIds)->all();

        $newArrayOfMainObjects=[];
        foreach($arrayOfMainObjects as $mainObject){
            //$filtered is array of objects (posts) that have user_id same as user->id
            $filtered=array_filter($arrayOfSecondClassModelObjects, function($secObj) use($mainObject, $mainIdentifier){
                return $mainObject->id===$secObj->$mainIdentifier;
            });
            //vardump($filtered);

            $newMainObject=new \stdClass();
            foreach($mainObject as $prop=>$value){
                $newMainObject->{$prop}=$value;
            }
            $newMainObject->{$newProperty}=$filtered;
            $newArrayOfMainObjects[]=$newMainObject;
        }//end foreach cousres as course
        return $newArrayOfMainObjects;


    }
}

/*

 */
<?php

require_once 'BaseModel.php';

class Treatment extends BaseModel
{
    public $name;
    public $description;
    public $registration_fee;
    public $treatment_fee;
    public $is_active;

    protected function getTableName()
    {
        return "treatments";
    }

    protected function addNewRec()
    {
        $param = array(
            ':name' => $this->name,
            ':description' => $this->description,
            ':treatment_fee' => $this->treatment_fee,
            ':registration_fee' => $this->registration_fee,
            ':is_active' => $this->is_active
        );
        return $this->pm->run(
            "INSERT INTO 
            treatments(name, description,treatment_fee, registration_fee, is_active) 
            values(:name, :description, :treatment_fee, :registration_fee, :is_active)",
            $param
        );
    }

    protected function updateRec()
    {
        $param = array(
            ':name' => $this->name,
            ':description' => $this->description,
            ':treatment_fee' => $this->treatment_fee,
            ':registration_fee' => $this->registration_fee,
            ':is_active' => $this->is_active,
            ':id' => $this->id
        );

        return $this->pm->run(
            "UPDATE 
            treatments 
            SET 
                name = :name, 
                description = :description,
                treatment_fee = :treatment_fee,
                registration_fee = :registration_fee,
                is_active = :is_active 
            WHERE id = :id",
            $param
        );
    }

    function createTreatment($name, $description, $treatment_fee, $registration_fee, $is_active = 1)
    {
        $treatmentModel = new Treatment();

        

        $treatment = new Treatment();
        $treatment->name = $name;
        $treatment->description = $description;
        $treatment->registration_fee = $registration_fee;
        $treatment->treatment_fee = $treatment_fee;
        $treatment->is_active = $is_active;
        $treatment->addNewRec();

        if ($treatment) {
            return $treatment; // treatment created successfully
        } else {
            return false; // tratment creation failed (likely due to database error)
        }
    }
    function update_treatment($id,$name, $description, $treatment_fee, $registration_fee, $is_active = 1)
    {
        $userModel = new Treatment();

       

        $treatment = new Treatment();
        $treatment->id = $id;
        $treatment->name = $name;
        $treatment->description = $description;
        $treatment->treatment_fee = $treatment_fee;
        $treatment->registration_fee = $registration_fee;
        $treatment->is_active = $is_active;
        $treatment->updateRec();

        if ($treatment) {
            return true; // Treatment udapted successfully
        } else {
            return false; // Treatment update failed (likely due to database error)
        }
    }
    function deletet_reatment($id)
    {
        $treatment = new User();
        $treatment->deleteRec($id);

        if ($treatment) {
            return true; // User udapted successfully
        } else {
            return false; // User update failed (likely due to database error)
        }
    }
}

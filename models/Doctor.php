<?php
require_once 'BaseModel.php';

class Doctor extends BaseModel
{
    public $name;
    public $about;
    public $photo;
    public $is_active;
    public $user_id;

    protected function getTableName()
    {
        return "doctors";
    }

    protected function addNewRec()
    {
        $param = array(
            ':name' => $this->name,
            ':about' => $this->about,
            ':photo' => $this->photo,
            ':is_active' => $this->is_active,
            ':user_id' => $this->user_id
        );

        return $this->pm->run("INSERT INTO doctors(name, about, photo, is_active, user_id) VALUES (:name, :about, :photo, :is_active, :user_id)", $param);
    }

    protected function updateRec()
    {
        $param = array(
            ':name' => $this->name,
            ':about' => $this->about,
            ':photo' => $this->photo,
            ':is_active' => $this->is_active,
            ':user_id' => $this->user_id,
            ':id' => $this->id
        );

        return $this->pm->run("UPDATE doctors SET name = :name, about = :about, photo = :photo, is_active = :is_active, user_id = :user_id WHERE id = :id", $param);
    }


    function createDoctor($name, $about, $user_id, $photo = null, $is_active = 1)
    {
        $doctorModel = new Doctor();
        $doctorModel->name = $name;
        $doctorModel->about = $about;
        $doctorModel->user_id = $user_id;
        $doctorModel->is_active = $is_active;
        $doctorModel->photo = $photo;
        $doctorModel->save();

        if ($doctorModel) {
            return true; // Doctor created successfully
        } else {
            return false; // Doctor creation failed (likely due to database error)
        }
    }

    function updateDoctor($id, $name = null, $about = null, $user_id = null, $photo = null, $is_active = null)
    {
        // Initialize the Doctor model
        $doctorModel = new Doctor();

        // Retrieve the doctor by ID
        $existingDoctor = $doctorModel->getById($id); // Assuming findById method exists

        if (!$existingDoctor) {
            return false; // Doctor not found
        }

        // Update properties only if values are provided
        if ($name !== null) {
            $existingDoctor->name = $name;
        }
        if ($about !== null) {
            $existingDoctor->about = $about;
        }
        if ($user_id !== null) {
            $existingDoctor->user_id = $user_id;
        }
        if ($photo !== null) {
            $existingDoctor->photo = $photo;
        }
        if ($is_active !== null) {
            $existingDoctor->is_active = $is_active;
        }

        // Save the changes
        $updated = $existingDoctor->save(); // Assuming save method exists

        return $updated ? true : false;
    }
}

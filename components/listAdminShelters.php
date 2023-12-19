
<?php

function listAdminShelters($shelters, $sheltersCount = 0){
    $sheltersList = "";

    if ($sheltersCount > 0) {
        foreach ($shelters as $shelter) {
            // Check the status and store the value with its color into a variable
            if ($shelter['status'] == "pending") {
                $status = "
                    <span class='badge rounded-pill text-bg-danger d-inline'>$shelter[status]</span>
                ";
            } elseif ($shelter['status'] == "accepted") {
                $status = "
                    <span class='badge rounded-pill text-bg-success d-inline'>$shelter[status]</span>
                ";
            }
            // // Check vaccination and store the value as an icon into a variable
            // if ($shelter['vaccination'] == "yes") {
            //     $vaccination = "
            //         <i class='fa-solid fa-check success'></i>
            //     ";
            // } else {
            //     $vaccination = "
            //         <i class='fa-solid fa-xmark danger'></i>
            //     ";
            // }
    
            // $years = grammarCheck($shelter['age'], 'year');
    
            // Create table content of shelters belonging to a the corresponding shelter dashboard
            $sheltersList .= "
                <tr>
                    <td class='ps-5'>
                        <div class='d-flex align-items-center'>
                            <img
                            src='../resources/img/shelters/$shelter[image]'
                            alt='$shelter[shelter_name]'
                            class='tablePic rounded-circle'
                            style='object-fit: cover'
                            />
                            <div class='ms-3'>
                                <p class='fw-bold mb-1'>$shelter[shelter_name]</p>
                            </div>
                        </div>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$shelter[capacity]</p>
                    </td>
                    <td class='text-center'>
                        <p style='max-width: 300px;' class='fw-normal mb-1 text-truncate'>$shelter[description]</p>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$shelter[zip]</p>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$shelter[country]</p>
                    </td>
                    <td class='actions text-center'>
                        <a class='px-1' href='shelters/edit.php?id=$shelter[sheltersID]'><i class='fa-sharp fa-solid fa-pen-nib'></i></a>
                        <a class='px-1' href='shelters/delete.php?id=$shelter[sheltersID]'><i class='fa-regular fa-trash-can'></i></a>
                    </td>
                    </tr>
                    ";

        }
    } else {
        return false;
    }
    return $sheltersList;

}
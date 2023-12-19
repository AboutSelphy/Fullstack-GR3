
<?php

function listAdminUsers($users, $usersCount = 0){
    $usersList = "";

    if ($usersCount > 0) {
        foreach ($users as $user) {
            // Check the status and store the value with its color into a variable
            if ($user['status'] == "admin") {
                $status = "
                    <span class='badge rounded-pill text-bg-danger d-inline'>$user[status]</span>
                ";
            } elseif ($user['status'] == "user") {
                $status = "
                    <span class='badge rounded-pill text-bg-success d-inline'>$user[status]</span>
                ";
            } else {
                $status = "
                    <span class='badge rounded-pill text-bg-warning d-inline'>$user[status]</span>
                ";
            }
            // // Check vaccination and store the value as an icon into a variable
            // if ($user['vaccination'] == "yes") {
            //     $vaccination = "
            //         <i class='fa-solid fa-check success'></i>
            //     ";
            // } else {
            //     $vaccination = "
            //         <i class='fa-solid fa-xmark danger'></i>
            //     ";
            // }
    
            // $years = grammarCheck($user['age'], 'year');
    
            // Create table content of users belonging to a the corresponding shelter dashboard
            $usersList .= "
                <tr>
                    <td class='ps-5'>
                        <div class='d-flex align-items-center'>
                            <img
                            src='../resources/img/users/$user[image]'
                            alt='$user[first_name]'
                            class='tablePic rounded-circle'
                            />
                            <div class='ms-3'>
                                <p class='fw-bold mb-1'>$user[first_name]</p>
                                <p class='text-muted mb-0'>$user[last_name]</p>
                            </div>
                        </div>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$user[email]</p>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$user[address]</p>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$user[zip]</p>
                    </td>
                    <td class='text-center'>
                        <p class='fw-normal mb-1'>$user[country]</p>
                    </td>
                    <td class='text-center'>
                        $status
                    </td>
                    <td class='actions text-center'>
                        <a class='px-1' href='users/edit.php?id=$user[userID]'><i class='fa-sharp fa-solid fa-pen-nib'></i></a>
                        <a class='px-1' href='users/delete.php?id=$user[userID]'><i class='fa-regular fa-trash-can'></i></a>
                    </td>
                    </tr>
                    ";

        }
    } else {
        return false;
    }
    return $usersList;

}
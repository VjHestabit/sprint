<!DOCTYPE html>
<html>
<head>
    <title>UnApproved User List</title>
</head>
<body>
    <table style="border: 1px solid #000000">
        <thead style="border: 1px solid #000000">
            <tr style="border: 1px solid #000000">
                <th>#</th>
                <th>Role</th>
                <th>Name</th>
                <th>E-mail</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody style="border: 1px solid #000000">
            @foreach ($details['data'] as $key => $data)
                <tr style="border: 1px solid #000000">
                    <td>{{ ++$key }}</td>
                    <td>{{ CustomHelper::$userType[$data->role_id] }}</td>
                    <td>{{ $data->first_name }}  {{ $data->last_name }}</td>
                    <td>{{ $data->email }}</td>
                    <td>{{ CustomHelper::$getUserStatus[$data->status] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <p>Thank you</p>
</body>
</html>

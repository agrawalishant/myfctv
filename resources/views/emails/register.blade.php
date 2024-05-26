<!Doctype html>
<html>
    <head>
        <title></title>
    </head>
    <body>
        <table>
            <tr>
                <td>Dear {{ $name }}</td>
            </tr>
            <tr>
                <td>Welcome to the team! We are thrilled to have you at Atpati Films. We know you’re going to be a valuable asset to our company and can’t wait to see what you accomplish. </td>
            </tr>
            <tr>
                <td>Your Account is activated and details are as below :-</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Name: {{ $name }}</td>
            </tr>
            <tr>
                <td>Mobile: {{ $mobile }}</td>
            </tr>
            <tr>
                <td>Email: {{ $email }}</td>
            </tr>
            <tr>
                <td>Password: {{ $password }}</td>
            </tr>
           
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td><strong>Note : </strong></td>
                
            </tr>
            <tr>
                <td style="color:grey;"><li>NEVER share your password or leave it visible at your workstation. If needed; your IT Department will request a temporary one be created while we troubleshoot issues under your User profile.</li>
                <li>NEVER leave your laptop or cell phone in your car, or anywhere, unattended in plain sight. Please take the laptop with you!</li>
                </td>
            </tr>
            
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>Thanks & Regards,</td>
            </tr>
            <tr>
                <td>Atpati Films</td>
            </tr>
        </table>
    </body>
</html>
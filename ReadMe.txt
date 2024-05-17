Library Management System (Increment 3) - 25/4/2024


General Usage Notes
-------------------------------------------
This Library Management System allows:
 - User login and authenticate their account.
 - User reset their password.
 - User view all books in the system.
 - User borrow, return, reserve, the books.
 - User cancel reservation.
 - User view borrow and reserve history.
 - User extend due date of borrowed books
 - User pay fine for the overdue book.
 - User get informed for fines and borrowed books that is due in less than 3 days via email and notification.
 - User view their profile.
 - User view the FaQ.
 - Librarian can view statistic and reports.
 - Librarian view book, user, issued, reserve, fine database.
 - Librarian add new book and new user.
 - Librarian edit book and user.
 - Librarian archive user and archive book.
 - Librarian register borrow and return books.
 - Librarian register reservation.
 - Librarian issued fine and pay patron's fine.
 - Librarian edit the library system's setting.
 - Librarian view profile.
 - Librarian view the FaQ.
 - Librarian reset password.
 - Admin view user and book database.
 - Admin add, update, delete user and books.
 - Admin view logs.
 - Admin view all librarian links.
 - Admin edit library system's setting.
 - Admin view profile.
*Note that the automated email sender is not linked to a live server.

Installation Notes
-------------------------------------------
 - Extract the folder "group1_library_management_system" in C:\...\xampp\htdocs\"file here".
 - Ensure your XAMPP Apache and MySQL is running.
 - Import the library_system.sql into your MySQL database.
 - Change the port number in connect.php, connect-edit.php, connect-mysqli.php and connect-login.php to the port number you are using.
 - Make sure to have the plugins folder(bookscanner, fpdf, PHPMailer) with the all the php and css files. The plugins are used for mailing.
 - Must: You need to add your email account with 16-digit passcode in add_new_user.php, send_otp_code.php, reset_password.php, query_update_user.php, payment_method.php, payment_method_librarian.php at this line:
   *You can follow this link (https://support.google.com/accounts/answer/185833?hl=en) to create a 16-digit passcode for your email.
	$mail->Username = " *email address* ";
        $mail->Password = " *email password* ";
        $mail->SetFrom(" *email address* ", " *user name* ");
 - To run the overdueEmail.py:
        1. write "pip install schedule" to install the "schedule" library
        2. change the email (gmail account) and password in the file
                email_sender = ' *email address* '
                email_password = '*email password* '
		email_receiver = '*receiver email*'
 - If the program does not run, turn off the firewall/anti-virus on your web browser.

Now you should be able to run the program successfully.


Start The Program
-------------------------------------------
To start the library management system, the url should be localhost/group1_library_management_system/login.php

If you want to test regular user's side:
email : st6g19@soton.ac.uk
password : aaaaa

If you want to test librarian's side:
email : librarian@soton.ac.uk
password : 123456

If you want to test admin's side:
email : zhc1e22@soton.ac.uk
password : aaaaa

email : kxt1g21@soton.ac.uk
password : bbbbb

email : hph1g21@soton.ac.uk 
password : ddddd

Contact Info
-------------------------------------------
Email 1 : kxt1g21@soton.ac.uk
Email 2 : zhc1e22@soton.ac.uk
Email 3 : hph1g21@soton.ac.uk 




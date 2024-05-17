import schedule
import time
import smtplib
import ssl
from email.message import EmailMessage
import os

#email_sender = '***library email***' #only gmail
#email_password = '***library password***'
#email_receiver = '***receiver email***'

email_sender = 'survivalqueen27@gmail.com'
email_password = 'wmfa ujbm nlrx cmgy'
email_receiver = 'zhc1e22@soton.ac.uk'

subject = 'Book Soon To Be Overdue'
body = """
Your book is soon to be overdue in 3 days. Please ensure you return it within 3 days. You will be charged our overdue fine per day if you fail to do so. Thank you for your cooperation.

For any inquiries, please reach out to our librarian.
"""

def mail():
    em = EmailMessage()
    em['From'] = email_sender
    em['To'] = email_receiver
    em['Subject'] = subject
    em.set_content(body)

    context = ssl.create_default_context()

    # Log in and send the email
    with smtplib.SMTP_SSL('smtp.gmail.com', 465, context=context) as smtp:
        smtp.login(email_sender, email_password)
        smtp.sendmail(email_sender, email_receiver, em.as_string())

# Schedule the email to be sent once
mail()

# You might want to change the interval or remove the scheduling loop entirely
# If you want to schedule it to run every day at midnight, uncomment the line below
schedule.every().day.at("11:42:00").do(mail)

# Run the scheduling loop
while True:
    schedule.run_pending()
    time.sleep(1)

#!/usr/bin/python

import smtplib

sender = 'isingh@jcvi.org'
receivers = ['xwnugpmnfz@gmail.com']

message = """From: isingh@jcvi.org
To: To Person <isingh@jcvi.org>
Subject: SMTP e-mail test

This is a test e-mail message.
"""

try:
   smtpObj = smtplib.SMTP('localhost')
   smtpObj.sendmail(sender, receivers, message)         
   print "Successfully sent email"
except SMTPException:
   print "Error: unable to send email"

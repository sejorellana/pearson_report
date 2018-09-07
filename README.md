# Pearson Reports
The following script allows the generation of two reports, the Queue wise report and the agent performance report
# How to deploy
## Before start be sure you have: 
> - PHP Web Server
> - Git
> - SMTP server
> - SMTP Credentials
> - Cx account enable for the API
> - Be part of the tenant you want to get reports
## Steps
 1. Got to the folder where you are going to deploy the tool
 2. Clone the project
 3. Be sure you are in the master branch
 4. Go to /Setup
 5. Rename the file named "setup.dist" to "setup.json"
 6. Rename the file named "mail.json.dist" to "mail.json"
 7. Fill the variables in setup.json as is show:
```sh
    "userId": "CxUserId",
    "username": "CxUsername",
    "password": "CxUserPassword",
    "tenantId": "CxTenantId",
    "tenantName": "CxTenantName",    
    "ftpUser": "FtpUsername",    
    "ftpPassword": "FtpPassword",    
    "ftpPort": "21",    
    "ftpFolder": "Folder/where/the/report/are/going/to/be/sent"  
```
 8. Fill the variables in mail.json as is show:
```sh
    "to": "email@domain.com",
    "from": "sender@domain.com",
    "cc": "email1@domain.com,email2@domain.com,emailn@domain.com",
    "replyTo": "replyEmail@domain.com",
    "subject": "Subject wanted for the error emails",
    "smtpHost": "smptHostName or smtIp",
    "smtpPort": "21",
    "smtUser": "user@domain.com",
    "smtpPassword": "usePassword"
```
 9. Set up the cron-jobs that are going to be executed each 30 minutes as show:
```sh
*/30 * * * * /pearson-reports/Web/agent.php >/dev/null 2>&1
*/30 * * * * /pearson-reports/Web/queuewise.php >/dev/null 2>&1
```
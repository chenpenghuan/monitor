from email.header import Header
from email.mime.text import MIMEText
from email.utils import parseaddr, formataddr
import smtplib
from time import sleep

def _format_addr(s):
    name, addr = parseaddr(s)
    return formataddr((Header(name, 'utf-8').encode(), addr))
def send_mail(from_addr='13919636933@139.com',password='575chenpenghuan',to_addr='cphchenpenghuan@gmail.com,1034478083@qq.com',smtp_server='smtp.139.com',content='邮件正文',from_user='发件人',to_user='云集管理员',title='邮件标题'):
    msg = MIMEText(content, 'plain', 'utf-8')
    msg['From'] = _format_addr(from_user+' <%s>' % from_addr)
    msg['To'] = _format_addr(to_user+' <%s>' % to_addr)
    msg['Subject'] = Header(title, 'utf-8').encode()
    server = smtplib.SMTP(smtp_server, 25)
    server.set_debuglevel(0)
    server.login(from_addr, password)
    server.sendmail(from_addr, to_addr.split(','), msg.as_string())
    server.quit()
if __name__=="__main__":
    for i in range(5):
        sleep(30)
        send_mail()



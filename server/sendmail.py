from email import encoders
from email.header import Header
from email.mime.text import MIMEText
from email.utils import parseaddr, formataddr
import smtplib
def send_mail(nameadd='Python爱好者 <13919636933@139.com>',content='你是不是傻?',fromuser='陈鹏欢',touser='云集管理员',title='是不是傻',toaddrs=['1034478083@qq.com']):
    name,add=parseaddr(nameadd)
    msg = MIMEText(content, 'plain', 'utf-8')
    msg['From'] = formataddr((Header(fromuser,'utf-8').encode(),'13919636933@139.com'))
    msg['To'] = formataddr((Header(touser,'utf-8').encode(),'857516325@qq.com'))
    msg['Subject']=Header(title,'utf-8').encode()
    server = smtplib.SMTP('smtp.139.com', 25) # SMTP协议默认端口是25
    server.set_debuglevel(0)
    server.login('13919636933@139.com','573chenpenghuan')
    server.sendmail('13919636933@139.com', toaddrs, msg.as_string())
    server.quit()
if __name__=="__main__":
    send_mail(toaddrs=['1034478083@qq.com','cphchenpenghuan@gmail.com'])

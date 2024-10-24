# O365Integration
Php ile OAuth2 kullanarak PHP ile Microsoft 365/Office 365 SMTP yapılandırması, Example code for configuring Microsoft 365/Office 365 SMTP and email operations using PHP with OAuth2.

Bu kodu kullanmak için aşağıdaki adımları takip etmelisiniz:

Öncelikle Microsoft Azure Portal'da bir uygulama kaydı oluşturmanız gerekiyor:

Azure Portal'da bir uygulama kaydı yapın
Gerekli OAuth2 izinlerini ekleyin (SMTP.Send, Mail.Read, Mail.Send)
Redirect URI'ı yapılandırın
Client ID ve Client Secret'ı alın


Composer ile gerekli paketleri yükleyin:
composer require league/oauth2-client thenetworg/oauth2-azure phpmailer/phpmailer

config.php dosyasındaki sabitleri kendi değerlerinizle güncelleyin:

CLIENT_ID
CLIENT_SECRET
TENANT_ID
REDIRECT_URI


Kod şu özellikleri içeriyor:

OAuth2 yetkilendirme
Access token alma ve yenileme
SMTP üzerinden mail gönderme
Microsoft Graph API ile mail okuma
Hata yönetimi

composer.json: Gerekli PHP paketlerini tanımlar.
config.php: Uygulama yapılandırma bilgilerini içerir. Azure Portal'dan aldığınız bilgileri buraya girmelisiniz.
auth.php: OAuth2 yetkilendirme işlemlerini yönetir.
mailer.php: E-posta gönderme işlemlerini yönetir. HTML mail ve dosya eki desteği içerir.
mail_reader.php: Microsoft Graph API kullanarak e-posta okuma ve arama işlemlerini yönetir.
callback.php: OAuth2 yetkilendirme sonrası callback işlemlerini yönetir.
index.php: Ana uygulama dosyası, örnek kullanımı gösterir.

..:: Onder Monder ::..

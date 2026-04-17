<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
</head>
<body style="margin:0; padding:0; background:#f6f1ea; font-family:Arial, Helvetica, sans-serif; color:#221f1a;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f1ea; padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px; background:#ffffff; border-radius:24px; overflow:hidden; border:1px solid #efe5d6;">
                    <tr>
                        <td style="padding:40px 40px 24px; background:linear-gradient(135deg, #fff7f1 0%, #ffede3 100%);">
                            <div style="font-size:13px; font-weight:700; letter-spacing:0.22em; text-transform:uppercase; color:#ff6a1a;">Mi Blog</div>
                            <h1 style="margin:18px 0 0; font-family:Georgia, serif; font-size:38px; line-height:1.05; color:#221f1a;">
                                Gracias por suscribirte
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px 40px 40px;">
                            <p style="margin:0 0 18px; font-size:17px; line-height:1.8; color:#4f4a43;">
                                Hola, <strong>{{ $email }}</strong>.
                            </p>
                            <p style="margin:0 0 18px; font-size:17px; line-height:1.8; color:#4f4a43;">
                                Ya quedaste suscrito a nuestro newsletter. A partir de ahora recibirás nuevos artículos, ideas y recursos sobre ecommerce, marketing, ventas y crecimiento digital.
                            </p>
                            <p style="margin:0 0 24px; font-size:17px; line-height:1.8; color:#4f4a43;">
                                Queremos que cada correo te aporte algo útil, claro y aplicable. Nada de ruido, solo contenido que ayude a tomar mejores decisiones y vender mejor.
                            </p>
                            <a href="{{ route('home') }}" style="display:inline-block; padding:14px 24px; border-radius:999px; background:#ff6a1a; color:#ffffff; text-decoration:none; font-weight:700;">
                                Ir al blog
                            </a>
                            <p style="margin:28px 0 0; font-size:14px; line-height:1.8; color:#7b746b;">
                                Si no fuiste tú quien se suscribió, puedes ignorar este mensaje.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Pembayaran Berhasil</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<style>
.btn {
  display: inline-block;
  font-weight: 400;
  color: #212529;
  text-align: center;
  vertical-align: middle;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 0.9rem;
  line-height: 1.6;
  border-radius: 0.25rem;
  transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
}
.btn-primary {
  color: #FFFFFF;
  background: #0052FF linear-gradient(180deg, #256bff, #0052FF) repeat-x;
  border-color: #0052FF;
}
</style>
</head>
<body style="margin: 0; padding: 0; background: #ccc; font-family: 'Nunito', sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td width="20%">&nbsp;</td>
            <td width="60%" style="padding: 20px 0;">
                <table width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="font-size: 14px">
                    <tr>
                        <td style="background: #0052FF; padding: 5px 0;" align="center">
                            <img src="https://semangatbantu.com/assets/img/logo-header.png" height="36" width="auto" alt="SemangatBantu.com">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 5px 10px">
                            <p><strong>AAJARAKALLAHU FIIMAA A&rsquo;THAITA WA BAARAKA LAKA FIIMAA ABQAITA WA JA&rsquo;ALAHU LAKA THAHUURAN</strong></p>
                            <p>"Semoga Allah memberi pahala atas apa yang telah engkau berikan, melimpahkan berkah terhadap hartamu yang tersisa dan menjadikannya penyuci bagimu ".</p>
                            <p>Terima kasih <strong>{{ $donasi->funder_name }}!</strong> 😊</p>
                            <p>Donasi Anda sebesar <strong>Rp {{ number_format($donasi->amount_final,0,',','.') }}</strong> sudah diterima untuk penggalangan dana program <strong>{{ $donasi->program->name }}</strong> di <a href="https://semangatbantu.com/p/{{ $donasi->program->link }}"><strong>sini</strong></a> 👈</p>
                            <p>Yuk, bantu <strong>#SemangatBantu</strong> dengan menyebarkan penggalangan ini ke orang-orang terdekat Anda. 🗣️</p>
                        </td>
                    </tr>
                </table>
            </td>
            <td width="20%">&nbsp;</td>
        </tr>
    </table>
</body>
</html>
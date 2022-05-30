<?php
ini_set('max_execution_time', 0);
ignore_user_abort();

$index = 0;
if (isset($_REQUEST['user'])) {
    $index = $_REQUEST['user'];
}
$url = "access_notify.php?user=exit";

$members = [
    ['name' => 'welington brito conceição', 'email' => 'davejunioroficial@gmail.com', 'phone' => '+5541997979988'],
    ['name' => 'Jean Carlos Ferreira Da Silva', 'email' => 'jancarlos91@hotmail.com', 'phone' => '+5544999575777'],
    ['name' => 'francisco canindé gerlandio de souza', 'email' => 'gerlandio@gmail.com', 'phone' => '+5555849983277'],
    ['name' => 'Lara Laubert', 'email' => 'laralaubert@gmail.com', 'phone' => '+5512988346868'],
    ['name' => 'Elizângela amaral santos', 'email' => 'elizangela_amarall@hotmail.com', 'phone' => '+5535991136140'],
    ['name' => 'MARIANA S OLIVEIRA', 'email' => 'adm100mariana@gmail.com', 'phone' => '+5562998384544'],
    ['name' => 'Diego Gonçalves Firmino', 'email' => 'diego.gfirmino@gmail.com', 'phone' => '+5527999470880'],
    ['name' => 'Robson Victor Vila Nova', 'email' => 'victorvilanova@gmail.com', 'phone' => '+5581999939475'],
    ['name' => 'Mary Da Silva Bento', 'email' => 'mysilva7@gmail.com', 'phone' => ''],
    ['name' => 'Faycal Samy Boumaza', 'email' => 'samgamerbr@gmail.com', 'phone' => '+5548998520400'],
    ['name' => 'Alexandre Henrique Pereira Marques', 'email' => 'alexandrehpmarques@gmail.com', 'phone' => '+5511984218581'],
    ['name' => 'eduardo pereira martini', 'email' => 'dudumartini@hotmail.com', 'phone' => '+5541996448494'],
    ['name' => 'Érika', 'email' => 'erika_ximenes@yahoo.com.br', 'phone' => '+5521967556923'],
    ['name' => 'Rosana Miyoshi', 'email' => 'rosanakimi@yahoo.com.br', 'phone' => '+5511961643024'],
    ['name' => 'Tamires Meneses Melo', 'email' => 'aobradecasal@gmail.com', 'phone' => '+5537999326244'],
    ['name' => 'LUCAS CARVALHO PAIVA', 'email' => 'lucaspaiva.md@gmail.com', 'phone' => '+5561993737930'],
    ['name' => 'Helenita R S Brito', 'email' => 'helenitabrito@gmail.com', 'phone' => '+5519971611190'],
    ['name' => 'LUIS SERGIO NISHIZAWA', 'email' => 'naschtisch@yahoo.com.br', 'phone' => '+5512982647079'],
    ['name' => 'Diego Ferraz Monteiro', 'email' => 'diegoferrazmonteiromarketing@gmail.com', 'phone' => '+5555996684339'],
    ['name' => 'Diogo José Xavier Ferreira', 'email' => 'diogojxferreira@hotmail.com', 'phone' => '+5591999666024'],
    ['name' => 'Denise Aparecida Matsuzaki', 'email' => 'denise.apmat@gmail.com', 'phone' => '+5511966267665'],
    ['name' => 'Jefferson Faria da costa', 'email' => 'jeffersons.personalizados@gmail.com', 'phone' => '+5521964745702'],
    ['name' => 'MYRIAN Carvalho de Souza Lima', 'email' => 'myrian5154@gmail.com', 'phone' => '+5527996285154'],
    ['name' => 'Clayton Sudário de Souza', 'email' => 'claytonss@hotmail.com', 'phone' => '+5567981049750'],
    ['name' => 'Emerson Celestino de Araujo', 'email' => 'emersoncelestino.ba@gmail.com', 'phone' => '+5574988076138'],
    ['name' => 'WAGNER R COSTA', 'email' => 'wagner.rodolfo@outlook.com', 'phone' => '+5511989681933'],
    ['name' => 'Anderson Sousa Ferreira', 'email' => 'agrodominios@gmail.com', 'phone' => '+5541999439062'],
    ['name' => 'Lucas da Silva Souza', 'email' => 'luca.silvasouza@hotmail.com', 'phone' => '+5511951327603'],
    ['name' => 'Gustavo Roberto Buyno', 'email' => 'gutobuyno@hotmail.com', 'phone' => '+5547991165644'],
    ['name' => 'Andrew Souza de Lima', 'email' => 'andrewdelimajpa@gmail.com', 'phone' => '+5583988016320'],
    ['name' => 'Cristiane Viana Machado', 'email' => 'crisviana2010@hotmail.com', 'phone' => '+5571992277648'],
    ['name' => 'Filemon Teixeira Junior', 'email' => 'filemontjr@gmail.com', 'phone' => '+5562984454377'],
    ['name' => 'Clayton da Silva Vieira', 'email' => 'pltclayton@gmail.com', 'phone' => '+5512997984961'],
    ['name' => 'THIAGO FIGUEIREDO BORGES', 'email' => 'thiagofigueiredoborges@gmail.com', 'phone' => '+5591987332742'],
    ['name' => 'Raphael Lopes Barbosa', 'email' => 'raphaelbarbosa2005@yahoo.com.br', 'phone' => '+5532998155781'],
    ['name' => 'GIOVANE MOREIRA SALES', 'email' => 'giovanemoreirasales1983@gmail.com', 'phone' => '+5531988786414'],
    ['name' => 'Alex Oliveira', 'email' => 'alextdoliveira@gmail.com', 'phone' => '+5511985914444'],
    ['name' => 'SIDNEI DE OLIVEIRA MUNIZ', 'email' => 'vivendaformen@gmail.com', 'phone' => '+5518996355308'],
    ['name' => 'Lucas moreira', 'email' => 'lucasmoreira4565@gmail.com', 'phone' => '+5527998693119'],
    ['name' => 'João Gabriel', 'email' => 'agencia.gapdigital@gmail.com', 'phone' => '+5583999387828'],
    ['name' => 'Etelvina Gonzalez Valera de Carvalho', 'email' => 'etelvina.digital@gmail.com', 'phone' => '+5511989355803'],
    ['name' => 'José Vitor da Silva Costa', 'email' => 'josevitor.dsc@gmail.com', 'phone' => '+5592984051228'],
    ['name' => 'Ilson Cristiano Monteiro Lara', 'email' => 'ilsonlara@gmail.com', 'phone' => '+5562999443533'],
    ['name' => 'IVALDO VIEIRA DE AZEVEDO FILHO', 'email' => 'ivaldoazevedo9@gmail.com', 'phone' => '+5521997927929'],
    ['name' => 'Gabriel Villar', 'email' => 'gvgabrielvillar@gmail.com', 'phone' => '+5511947785344'],
    ['name' => 'Dennison Franco', 'email' => 'dennison_augusto@hotmail.com', 'phone' => '+5583998035990'],
    ['name' => 'Anderson Nascimento', 'email' => 'berttoanderson@gmail.com', 'phone' => '+5571982392898'],
    ['name' => 'Vanessa Chaves de Carvalho Athayde', 'email' => 'vanessaccarvalho@gmail.com', 'phone' => '+5573999820171'],
    ['name' => 'Juan', 'email' => 'feramarketingads@gmail.com', 'phone' => '+5555889962973'],
    ['name' => 'Ruy Rocha da Silva', 'email' => 'ruy.rocha@outlook.com', 'phone' => '+5584987542112'],
    ['name' => 'Lucas Nikolai Candido da Silva', 'email' => 'lucas_nikolai@hotmail.com', 'phone' => '+5565992303980'],
    ['name' => 'Rony Souza Barbosa', 'email' => 'ronyandreasaph@bol.com.br', 'phone' => '+5527996671526'],
    ['name' => 'Priscilla da Conceição Oliveira', 'email' => 'oliveiraprii92@gmail.com', 'phone' => '+5555249998781'],
    ['name' => 'Maria Camila Queiros', 'email' => 'mcamilapq@yahoo.com.br', 'phone' => '+5519997245343'],
    ['name' => 'Avinho Apolinario', 'email' => 'djavsonap@gmail.com', 'phone' => '+5571993574825'],
    ['name' => 'Jessica Duarte Leite', 'email' => 'filha.do.rei.amada.do.pai@gmail.com', 'phone' => '+5521998797261'],
    ['name' => 'andresa almeida peixoto', 'email' => 'andresa.peixoto35@gmail.com', 'phone' => '+5544991126262'],
    ['name' => 'Thiago Pereira Germano', 'email' => 'Tpgermano@gmail.com', 'phone' => '+5521984745121'],
    ['name' => 'Leandro Luiz Costa Bordignon', 'email' => 'leandro.bordignon@gmail.com', 'phone' => '+5519997338479'],
    ['name' => 'Carla Carvalho de Sousa', 'email' => 'carlac_sp@yahoo.com.br', 'phone' => '+5531986794342'],
    ['name' => 'Elzilene dos Santos Saturnino', 'email' => 'Lennycssantos@gmail.com', 'phone' => '+5571996025496'],
    ['name' => 'Seily Custódio', 'email' => 'seilyscustodio@gmail.com', 'phone' => '+5511988424445'],
    ['name' => 'Lucia Cagido', 'email' => 'lcagido@gmail.com', 'phone' => '+5521996149911'],
    ['name' => 'Marcos Sitko', 'email' => 'marcos.sitko@yahoo.com.br', 'phone' => '+5541992293873'],
    ['name' => 'Paulo Henrique Guenka', 'email' => 'pauloguenka@yahoo.com.br', 'phone' => '+5551985479511'],
    ['name' => 'Paula Alexandra Soares da Silva Nunes', 'email' => 'paulajoso@gmail.com', 'phone' => '+5565996013801'],
    ['name' => 'Lenilson araujo reis', 'email' => 'lenilsonaraujoreis@hotmail.com', 'phone' => '+5531988951994'],
    ['name' => 'Jessica Chrysostomo da Silva', 'email' => 'jessica.chrysostomo@gmail.com', 'phone' => '+5516997207003'],
    ['name' => 'Daniela Pomin S Schuler', 'email' => 'dpomin@gmail.com', 'phone' => '+5511991067800'],
    ['name' => 'Cesar Alexandre Pereira', 'email' => 'cesarpereira.7@outlook.com', 'phone' => '+5519996670034'],
    ['name' => 'Alex veloso', 'email' => 'Veloso_ribeiro@hotmail.com.br', 'phone' => '+5527992334954'],
    ['name' => 'Luiz Carlos da Silva', 'email' => 'luizcs86@gmail.com', 'phone' => '+5561982058989'],
    ['name' => 'Marcelo Henrique', 'email' => 'Mcelohns@gmail.com', 'phone' => '+5531975761764'],
    ['name' => 'Jairo L S Barbosa', 'email' => 'jlsbpr@outlook.com', 'phone' => '+5543999447711'],
    ['name' => 'João Tarcísio de Oliveira', 'email' => 'jtarcisio100@gmail.com', 'phone' => '+5512997448181'],
    ['name' => 'Rodrigo Voloski Gomes', 'email' => 'contato@rodrigovoloski.com', 'phone' => '+5562999095915'],
    ['name' => 'Adalberto Dias', 'email' => 'adalberto.verbobh@gmail.com', 'phone' => '+5531993331554'],
    ['name' => 'Samuel Camargo', 'email' => 'Samuel.hbl@uol.com.br', 'phone' => '+5511981604140'],
    ['name' => 'Reginaldo Sant\'Anna Ribeiro', 'email' => 'regisrib79@hotmail.com', 'phone' => '+5511975095848'],
    ['name' => 'Edson Soares', 'email' => 'erodsoares@gmail.com', 'phone' => '+5521970097807'],
    ['name' => 'Fábio Vidoto Dutra', 'email' => 'fabiovdutra@gmail.com', 'phone' => '+5543996649978'],
    ['name' => 'Gustavo Nogueira de Andrade', 'email' => 'guh.mkt2021@gmail.com', 'phone' => '+5519995758148'],
    ['name' => 'Mary Ellen Solia', 'email' => 'maryellensolia@yahoo.com.br', 'phone' => '+5524999829944'],
    ['name' => 'PRISCILLA SANTOS', 'email' => 'spriss@gmail.com', 'phone' => '+5511995129151'],
    ['name' => 'Creuza Medeiros', 'email' => 'creuzamedeiros@gmail.com', 'phone' => '+5565992143920'],
    ['name' => 'Mauricio de Araújo Verçosa', 'email' => 'mvercosa@gmail.com', 'phone' => '+5583991326919'],
    ['name' => 'DASSAIEV MORAES DE JESUS', 'email' => 'dassaimj@gmail.com', 'phone' => '+5596981093016'],
    ['name' => 'Adalberto Dias', 'email' => 'adalberto.verbobh@gmail.com', 'phone' => '+553193331554'],
    ['name' => 'Tales Silva Borges', 'email' => 'talesbiologo@gmail.com', 'phone' => '+5534996606059'],
    ['name' => 'Fernando Borges', 'email' => 'nandomb@gmail.com', 'phone' => '+5521988494501'],
    ['name' => 'Daniel C M Junior', 'email' => 'bhdigitalmarketing@gmail.com', 'phone' => '+5527992543663'],
    ['name' => 'Augusto César Campos Filho', 'email' => 'gutohumano@gmail.com', 'phone' => '+5565981345242'],
    ['name' => 'Neriton Dias', 'email' => 'neriton.dias@live.com', 'phone' => '+5562994325582'],
    ['name' => 'Guilherme Augusto Marques Araujo', 'email' => 'garaujosp@gmail.com', 'phone' => '+5511984479826'],
    ['name' => 'LUCAS RIBEIRO WALTRICH', 'email' => 'lr.waltrichbk@gmail.com', 'phone' => '+5551991947027'],
    ['name' => 'Fábio Alves Pereira', 'email' => 'fabiomocmg@gmail.com', 'phone' => '+5538991947190'],
    ['name' => 'Paulo Roberto Linhares', 'email' => 'paulo.linhares@gmail.com', 'phone' => '+5539991667081'],
    ['name' => 'Julia Junges', 'email' => 'juliajungesjuba@gmail.com', 'phone' => '+5555981411119'],
    ['name' => 'Patricia Florence Gasparian', 'email' => 'pat.gasparian@gmail.com', 'phone' => '+5551981403175'],
    ['name' => 'Simone Azeredo Goldner Rodrigues', 'email' => 'sigoldner@gmail.com', 'phone' => '+5521980508458'],

];

if ($index !== "exit") {

    if (array_key_exists($index, $members)) {

        $name = ucwords(strtolower($members[$index]['name']));
        $phone = $members[$index]['phone'];
        $email = $members[$index]['email'];

        $message = "Fala $name, tranquilo?" . PHP_EOL . PHP_EOL;
        $message .= "⚠️*Preciso abrir o jogo com você, algumas coisas tem me deixado extremamente chateado...*" . PHP_EOL . PHP_EOL;
        $message .= "🤯  Leonardo Scapinello por aqui e estou te mandando essa mensagem por ser algo *EXTREMAMENTE IMPORTANTE*, afinal *AINDA TEM ALUNOS QUE NÃO ENTRARAM EM MEU GRUPO DO DISCORD* e estou recebendo inúmeras mensagems me cobrando disso." . PHP_EOL . PHP_EOL;
        $message .= "⏳ Por isso, estou enviando essa mensagem para ter certeza que você está em nosso grupo do DISCORD referente ao meu curso *MEU PRIMEIRO PLR*, que atualmente se chama *WEBPRO* adquirido por você há um tempinho atrás." . PHP_EOL . PHP_EOL;
        $message .= "➡ A primeiro atualização refere-se à ter recebido INÚMERAS reclamações, então por esse motivo estou levando todo o curso para a área de membros da KIWIFY e você conseguirá acessar normalmente através do seu login (" . $email . ") e senha." . PHP_EOL . PHP_EOL;
        $message .= "➡ A segunda atualização está referente a nosso grupo no discord, que refaço este convite à você. Todas as atualizações, aulas e encontros ao vivo (que estou liberando como bônus) estão acontecendo por lá" . PHP_EOL . PHP_EOL;
        $message .= "➡ E para você garantir seu acesso, basta clicar neste link: https://discord.gg/Tn2YGpHcct" . PHP_EOL . PHP_EOL;
        $message .= "📍 Lembrando: Se o link estiver desabilitado para você, responsa essa mensagem com \"Ok\" e o link será liberado para clicar." . PHP_EOL . PHP_EOL;
        $message .= "😡 Agora só depende de você.";

        echo "<div style='max-width:500px;'>";
        echo "<h1>Notifying: " . $name . "</h1><hr>";
        echo "Phone: " . $phone . "<br>";
        echo "E-mail: " . $email . "<br>";
        echo "Datetime: " . date("d/m/Y H:i:s") . "<br>";
        echo "Message <br>";
        echo "<pre style='max-width:500px;white-space: break-spaces'>" . $message . "</pre>";
        echo "</div>";


        $array = array(
            "content" => $message,
            "contact[phone]" => $phone
        );

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://webhook.zapcloud.com.br/webhook/6c423f4f935e0965168dcc46371e051a",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($array),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);


        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $url = "access_notify.php?user=" . ($index + 1);
        }

    }

    ?>
    <script>
        window.setTimeout(function () {
            window.location.href = "<?=$url?>";
        }, 30000);
    </script>
<?php } ?>
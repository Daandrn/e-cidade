<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Oc23824 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dados = [
            "211110401%",
            "211110402%",
            "211110403%",
            "211110501%",
            "211110502%",
            "211110503%",
            "2111107%",
            "211210401%",
            "211210402%",
            "211210403%",
            "211210501%",
            "211210502%",
            "211210503%",
            "2112107%",
            "211310301%",
            "211310302%",
            "211310303%",
            "211310401%",
            "211310402%",
            "211310403%",
            "211410102%",
            "2114106%",
            "2114107%",
            "2114109%",
            "211420201%",
            "211420202%",
            "211430102%",
            "2114306%",
            "2114307%",
            "2114406%",
            "2114506%",
            "212110101%",
            "212110102%",
            "212110198%",
            "212110201%",
            "212110298%",
            "2121103%",
            "212130101%",
            "212130102%",
            "212130198%",
            "212130201%",
            "212130298%",
            "212130401%",
            "212130501%",
            "2121399%",
            "212140101%",
            "212140102%",
            "212140198%",
            "212140201%",
            "212140298%",
            "2121403%",
            "212150101%",
            "212150102%",
            "212150198%",
            "212150201%",
            "212150298%",
            "2121503%",
            "2122101%",
            "2122102%",
            "2122103%",
            "212310101%",
            "212310102%",
            "212310103%",
            "212310198%",
            "2123102%",
            "21232%",
            "212330101%",
            "212330102%",
            "212330103%",
            "212330198%",
            "2123302%",
            "212340101%",
            "212340102%",
            "212340103%",
            "212340198%",
            "2123402%",
            "21235%",
            "212410101%",
            "212410102%",
            "212410198%",
            "2124102%",
            "2125101%",
            "2125102%",
            "2125103%",
            "2125104%",
            "2125203%",
            "2125204%",
            "212530101%",
            "212530199%",
            "212530201%",
            "212530299%",
            "2125303%",
            "2125304%",
            "2125401%",
            "2125402%",
            "2125403%",
            "2125404%",
            "2125501%",
            "2125502%",
            "2125503%",
            "2125504%",
            "2126101%",
            "2126102%",
            "2126103%",
            "2126104%",
            "2128101%",
            "2128102%",
            "2128301%",
            "2128302%",
            "2128401%",
            "2128402%",
            "21285%",
            "2129101%",
            "2129102%",
            "213110102%",
            "213110103%",
            "213110302%",
            "213110303%",
            "213110501%",
            "213110502%",
            "213110503%",
            "213110601%",
            "213110602%",
            "213110603%",
            "213110701%",
            "213110702%",
            "213110703%",
            "213110801%",
            "213110802%",
            "213110803%",
            "2131111%",
            "213210102%",
            "213210103%",
            "213210202%",
            "213210203%",
            "2141112%",
            "2141212%",
            "2141312%",
            "2142103%",
            "2142203%",
            "2142403%",
            "2143103%",
            "2143203%",
            "2143503%",
            "21811%",
            "21812%",
            "21813%",
            "21814%",
            "21815%",
            "2184101%",
            "2184102%",
            "2184196%",
            "2184197%",
            "2184198%",
            "2184199%",
            "21843%",
            "21844%",
            "21845%",
            "2186101%",
            "218910105%",
            "218910108%",
            "218930105%",
            "218940105%",
            "218950105%",
            "221110301%",
            "221110302%",
            "221110303%",
            "221110401%",
            "221110402%",
            "221110403%",
            "2211107%",
            "221210201%",
            "221210202%",
            "221210203%",
            "221210301%",
            "221210302%",
            "221210303%",
            "221310201%",
            "221310202%",
            "221310203%",
            "221310301%",
            "221310302%",
            "221310303%",
            "2214101%",
            "2214102%",
            "2214103%",
            "2214201%",
            "221420201%",
            "221420202%",
            "221430101%",
            "2214302%",
            "222110101%",
            "222110102%",
            "222110198%",
            "222110298%",
            "2221103%",
            "222130101%",
            "222130102%",
            "222130198%",
            "222130298%",
            "222130401%",
            "222130501%",
            "2221399%",
            "222140101%",
            "222140102%",
            "222140198%",
            "222140298%",
            "2221403%",
            "222150101%",
            "222150102%",
            "222150198%",
            "222150298%",
            "2221503%",
            "2222101%",
            "2222102%",
            "2222103%",
            "222310101%",
            "222310102%",
            "222310103%",
            "222310198%",
            "2223102%",
            "22233%",
            "22234%",
            "22235%",
            "222410101%",
            "222410102%",
            "2224102%",
            "2224198%",
            "222510101%",
            "222510102%",
            "222510201%",
            "222510202%",
            "222510301%",
            "222510302%",
            "222510401%",
            "222510402%",
            "22252%",
            "22253%",
            "22254%",
            "22255%",
            "222610101%",
            "222610102%",
            "222610201%",
            "222610202%",
            "222610301%",
            "222610302%",
            "222610401%",
            "222610402%",
            "2228101%",
            "2228102%",
            "2228202%",
            "2228301%",
            "2228302%",
            "2228401%",
            "2228402%",
            "2228501%",
            "2228502%",
            "2229101%",
            "2229102%",
            "223110102%",
            "223110103%",
            "223110401%",
            "223110402%",
            "223110403%",
            "223110501%",
            "223110502%",
            "223110503%",
            "223110601%",
            "223110602%",
            "223110603%",
            "223110701%",
            "223110702%",
            "223110703%",
            "223111002%",
            "223111003%",
            "2231111%",
            "223210102%",
            "223210103%",
            "223210202%",
            "223210203%",
            "2241102%",
            "2241202%",
            "2241302%",
            "2242101%",
            "2242201%",
            "2242401%",
            "2243101%",
            "2243201%",
            "2243501%",
            "22811%",
            "22812%",
            "22813%",
            "22814%",
            "22815%",
            "22831%",
            "22833%",
            "22834%",
            "22835%",
            "2286101%"
        ];

        foreach ($dados as $dado) {
            DB::statement("UPDATE contabilidade.conplano SET c60_nregobrig = 29 WHERE c60_anousu = 2025 and c60_estrut LIKE ?", [$dado]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

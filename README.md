# Grouper

    　 　 　 　　　　　　　　　　　　　　　　　　　　　　　　 ,.へ
    　　___ 　　　　　　　 　 　 　 　 　　　　　　　　  　　ﾑ　　i
    　「 ﾋ_i〉　　　 　 　　　　　　 　 　　　　　　  　　　 ゝ　〈
    　ﾄ　ノ 　　　　　　　　　　　　　　　　　　 　　 　 　　　iニ(()
    　i 　{ 　 　　　　　　　 　　　＿＿＿_ 　 　　　　 　　　| 　ヽ
    　i　　i　　　 　　　　 　　／__,　 , ‐- ＼ 　 　 　 　　i  　}
    　|　　 i　　　　 　　 　／  （●) 　 (●)    ＼　　　　　 {､　 λ
    　ト－┤.　　  　　　　／ 　 　（__人__） 　　　＼　　　 ,ノ　￣ ,!
    　i　　　ゝ､_ 　　　　|　　　　　´￣` 　 　　　　|　,. '´ﾊ　　　,!
    .　ヽ、 　　　｀`　､,__＼ 　　 　 　　　　　 　 ／"　＼ 　ヽ／
    　　　＼ノ　ﾉ　　　ﾊ ￣   r/:::r―--     /::７　 ﾉ　　　　／
    　 　　 　 ヽ.　　  　　　ヽ::〈；.':. :'|:/   /　　,. "
    　　　　　　　 `ｰ ､  　　　　＼ヽ::. ;::：|/　　　ｒ'"
    　       　　　　／￣二二二二二二二二二二二二二二二二ヽ
    　       　　　　| 答 |　　　　G r o u p e r 　 　│|
           　　　　　＼＿二二二二二二二二二二二二二二二二ノ

##Android Studioへのインポート方法
1. Grouperフォルダを開きます
2. android studioでandroid-bootstrapをインポートします
たぶんおｋ！

##build時の設定
* 最小APKは**15**
* ターゲットAPIは**19**
* buildToolsVersionhは**19.1.0**
* いずれも**app/src/main/AndroidManifest.xml** や **app/build.gradle**で定義してあるから大丈夫なはず・・・？

##その他
* アプリのパッケージ名 : 暫定的に **org.sysken.grouper**
* サーバ : 現在調整中 - BAASBOXを利用 or MySQL
    BAASBOX : 楽 but 資料が全部英語
    MySQL : 資料が豊富 but らりょすの経験がない

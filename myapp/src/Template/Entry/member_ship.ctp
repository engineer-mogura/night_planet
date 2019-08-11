<div class="entry container">
<div class="row">
      <div class="col s12">
        <div class="card-panel deep-orange darken-2">
        <h4 class="white-text">店舗掲載のご案内</h4>
          <span class="white-text">
          <div>
            <p>掲載をご希望、又は検討されている経営者様へ</p>
            <p>【<?= LT['001'] ?>】では、県内特化型ポータルサイトとして、沖縄全域のナイト情報を提供するため(<span style="color: #ff0000;">※</span>ソープ、デリヘル等の風俗情報を除く)、２０１８年にサイトをオープンしました。</p>
            <p>今はまだ掲載店舗が少ないサイトではありますが、掲載してくださった経営者様と共に成長していきたいと考えております。</p>
            <p>今の時代、ユーザーは何処にどんなお店があるのかスマートフォンで検索する時代になりました。経営者様の店舗がどういった雰囲気のお店なのかわからず、入店を渋ってしまうお客様がいたとしたら、それは集客方法の改善が必要かも知れません。</p>
            <p>こちらのサイト【<?=LT['001']?>】は、WordPress（ワードプレス）といいます、 CMS（ コンテンツマネジメントシステム）というシステムで構築されております。WordPressは、google等の検索エンジンに非常に強く作られており経営者様の店舗も一発検索にヒットするような仕組みが備えられており、集客UPに非常に効果が期待できます。</p>
            <p>まずは半年間の無料掲載をしつつ、集客してみませんか？サイト運営者としてもまずは、コンテンツをどんどん増やしていくことが検索ユーザーにも有益な情報を提供できると考えております。</p>
            <blockquote>
                <p><?= __d('cake', 'どのように掲載されるのかは、下のURL先をサンプルとして用意しておりますのでご覧ください。')?></p>
            </blockquote>
                <div class="org-link">
                    <a href="http://okiyoru.local/naha/shop/56?area=naha&genre=cabacula&name=Club%E7%90%89%E7%90%83">Club琉球</a>
                </div>
            <blockquote>
                <p><?= __d('cake', '店舗掲載をご希望の方は、下のURLからユーザー登録をお願いします。')?></p>
            </blockquote>
            <div class="org-link">
                <a href="/entry/signup">新規登録</a>
            </div>
            <blockquote>
                <p><?= __d('cake', '既に会員の方は下のURLからログインしてください。')?></p>
            </blockquote>
            <div class="org-link">
                <?= $this->Html->link(__('ログインページへ'), ['controller' => 'owner/owners', 'action' => 'login']) ?>
            </div>
        </div>
        </span>
        </div>
      </div>
    </div>

</div>

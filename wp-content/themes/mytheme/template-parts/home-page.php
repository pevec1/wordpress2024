<article>
    <?php ///the_content(); 
    ?>
</article>
<main>
    <h1>Маршруты будущего</h1>
    <div class="content">
        <div>
            <?php
            echo do_shortcode('[smartslider3 slider="2"]');
            ?>
        </div>
        <div class="cards">
            <div class="card">
                <img class="card__image" src="https://i.postimg.cc/Gpp6ryr0/180103-ufo-illustration-mn-1015-0758c11fb1637ed3431613cef06cd246.jpg" alt="" />
                <div class="card__content">
                    <h3 class="card__title">Ершово</h3>
                    <p class="card__text">Lorem ipsum dolor sit amet consectetur adipisicing elit. Aperiam blanditiis temporibus, quos quia repellat saepe maxime repellendus, architecto expedita fugiat adipisci deserunt inventore esse ullam quibusdam accusamus voluptatibus enim incidunt!</p>
                    <div class="card__btns"><button class="card__btn">Далее</button>
                        <button class="card__btn">Записаться</button>
                    </div>
                </div>
            </div>


            <div class="chapter">
                <h3 сlass="chapter__title">Путешественнику</h3>
                <div class="chapter__content">Раздел с рекомендациями по посещению предприятий.</div>
                <a href="<?php echo get_page_link(8); ?>" class="chapter__btn">Посмотреть</a>
            </div>
        </div>
        <?php get_sidebar('sidebar-1'); ?>
    </div>
</main>
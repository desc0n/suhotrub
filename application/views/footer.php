<div id="footerOuterSeparator"></div>

<div id="divFooter" class="footerArea">

    <div class="divPanel">

        <div class="row-fluid">
            <div class="span9" id="footerArea1">
                <h3>О компании</h3>
                <?=$contacts['about'];?>
            </div>
            <div class="span3" id="footerArea4">
                <h3>Контакты</h3>
                <ul id="contact-info">
                    <li>
                        <i class="general foundicon-phone icon"></i>
                        <span class="field">Телефон:</span>
                        <br />
                        <?=$contacts['phone'];?>
                    </li>
                    <li>
                        <i class="general foundicon-mail icon"></i>
                        <span class="field">Email:</span>
                        <br />
                        <a href="mailto:<?=$contacts['email'];?>" title="Email"><?=$contacts['email'];?></a>
                    </li>
                    <li>
                        <i class="general foundicon-home icon" style="margin-bottom:50px"></i>
                        <span class="field">Адрес:</span>
                        <br />
                        <?=$contacts['address'];?>
                    </li>
                </ul>

            </div>
        </div>
    </div>
</div>
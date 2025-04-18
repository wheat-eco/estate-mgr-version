</div><!-- End content-wrapper -->
        </main><!-- End main-content -->
    </div><!-- End admin-container -->
    
    <script src="js/admin.js"></script>
    <?php if(isset($additionalJs)): ?>
        <?php foreach($additionalJs as $js): ?>
            <script src="js/<?php echo $js; ?>.js"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
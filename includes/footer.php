<?php 
// No whitespace before this PHP tag
?>
<footer class="site-footer">
    <div class="footer-content">
        <p class="copyright">&copy; <?= date('Y') ?> Quiz WebApp | Developed by Pravin Lanje</p>
    </div>
</footer>

<style>
    /* Minimal Footer Styles */

    .site-footer {
        background-color: #2c3e50;
        color: #ecf0f1;
        padding: 0.8rem 0;
        margin-top: auto; /* This pushes footer to bottom */
        width: 100%;
    }
    
    .footer-content {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
        padding: 0 1rem;
    }
    
    .copyright {
        margin: 0;
        font-size: 0.8rem;
        color: #bdc3c7;
    }
    
/*     
    @media (max-width: 768px) {
        .site-footer {
            padding: 0.8rem 0;
        }
        .copyright {
            font-size: 0.8rem;
        }
    } */
</style>
</body>
</html>
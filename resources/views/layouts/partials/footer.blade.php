<footer class="footer mt-auto py-3 bg-white text-center">
  <div class="container d-none d-md-block">
    <span class="text-muted"> Copyright © <span id="year"></span> <a href="javascript:void(0);" class="text-dark fw-semibold">{{ strtoupper(domain()) }}</a>.
      Developed by <a href="{{ setting('footer_link', 'https://www.cmsnt.co/?utm=smmpanelv3&domain=' . domain()) }}">
        <span class="fw-semibold text-primary text-decoration-underline">{{ setting('footer_text', 'CMSNT.CO LTD') }}</span>
      </a>
    </span>
  </div>
  <div class="container d-md-none" style="font-size: 10px">
    <span class="text-muted"> Copyright © <span id="year"></span> <a href="javascript:void(0);" class="text-dark fw-semibold">{{ strtoupper(domain()) }}</a>.
      Developed by <a href="{{ setting('footer_link', 'https://www.cmsnt.co/?utm=smmpanelv3&domain=' . domain()) }}">
        <span class="fw-semibold text-primary text-decoration-underline">{{ setting('footer_text', 'CMSNT.CO LTD') }}</span>
      </a>
    </span>
  </div>
</footer>

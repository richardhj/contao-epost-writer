var Outbox = function (totalCount, cycleTime, cyclePause) {
    var containerElement = $('epost_outbox');

    var failedCount = 0;
    var successCount = 0;
    var openCount = parseInt(totalCount);
    var timeout = 0;
    var duration = 0;

    var failedCountElement = $('epost_outbox_execution_failed');
    var successCountElement = $('epost_outbox_execution_success');
    var openCountElement = $('epost_outbox_execution_open');
    var timeoutElement = $('epost_outbox_execution_timeout');
    var durationElement = $('epost_outbox_execution_duration');

    var progressFailedElement = $('epost_outbox_progress_failed');
    var progressSuccessElement = $('epost_outbox_progress_success');
    var progressOpenElement = $('epost_outbox_progress_working');

    var timerTrigger = false;
    var timer = function () {
        duration++;
        durationElement.set('html', duration.formatTime(true, true));

        if (timeout > 0) {
            timeout--;
            timeoutElement.set('html', timeout.formatTime());
        }
        else {
            timeoutElement.set('html', '0:00');
        }

        timerTrigger = timer.delay(1000, this);
    };

    function setProgress(failed, success, open) {
        if (failed === undefined) {
            failed = Math.floor(failedCount / totalCount * 100);
        }
        if (success === undefined) {
            success = Math.floor(successCount / totalCount * 100);
        }
        if (open === undefined) {
            open = 100 - failed - success;
        }
        progressFailedElement.setStyle('width', failed + '%');
        progressSuccessElement.setStyle('width', success + '%');
        progressOpenElement.setStyle('width', open + '%');
    }

    var request = new Request.JSON({
        url: 'system/modules/epost-writer/assets/web/queue_execute.php',
        link: 'ignore',
        onRequest: function () {
            timeout = parseInt(cycleTime);
            timeoutElement.set('html', timeout.formatTime());
            containerElement
                .removeClass('initializing')
                .removeClass('waiting')
                .removeClass('finished')
                .addClass('running');
        },
        onSuccess: function (responseJSON, responseText) {
            if (!responseJSON || responseJSON.error) {
                // logged out
                if (responseText.indexOf('tl_login') > -1) {
                    window.location.reload();
                }

                // other error
                else {
                    window.clearTimeout(timerTrigger);
                    containerElement
                        .removeClass('initializing')
                        .removeClass('running')
                        .removeClass('waiting')
                        .removeClass('finished')
                        .addClass('errored');
                    setProgress(100, 0, 0);
                    $('epost_outbox_exception').setStyle('display', 'block');
                    $('epost_outbox_exception_text').set('text', responseText);
                }
            }
            else if ((responseJSON.failed + responseJSON.success) > 0) {
                failedCount += parseInt(responseJSON.failed);
                successCount += parseInt(responseJSON.success);
                openCount -= parseInt(responseJSON.failed + responseJSON.success);

                failedCountElement.set('text', failedCount.formatNumber());
                successCountElement.set('text', successCount.formatNumber());
                openCountElement.set('text', openCount.formatNumber());
                setProgress();

                containerElement
                    .removeClass('initializing')
                    .removeClass('running')
                    .removeClass('finished')
                    .addClass('waiting');

                timeout = parseInt(cyclePause);
                timeoutElement.set('html', timeout.formatTime());
                request.get.delay(cyclePause * 1000, request);
            }
            else {
                containerElement
                    .removeClass('initializing')
                    .removeClass('running')
                    .removeClass('waiting')
                    .addClass('finished');

                window.clearTimeout(timerTrigger);
                timeout = 0;
            }
        },
        onError: function (text, error) {
            window.clearTimeout(timerTrigger);
            containerElement
                .removeClass('initializing')
                .removeClass('running')
                .removeClass('waiting')
                .removeClass('finished')
                .addClass('errored');
            setProgress(100, 0, 0);
            $('epost_outbox_exception').setStyle('display', 'block');
            $('epost_outbox_exception_text').set('html', text);
        },
        onFailure: function (xhr) {
            if (204 == xhr.status) {
                // Follow a redirect (most likely to the OAuth provider)
                window.location.href = xhr.getResponseHeader('X-Ajax-Location')
            } else {
                window.clearTimeout(timerTrigger);
                containerElement
                    .removeClass('initializing')
                    .removeClass('running')
                    .removeClass('waiting')
                    .removeClass('finished')
                    .addClass('errored');
                setProgress(100, 0, 0);
                $('epost_outbox_exception').setStyle('display', 'block');
                var response = JSON.decode(xhr.response);
                $('epost_outbox_exception_text').set('html', response.error);
            }
        }
    });

    (function () {
        timerTrigger = timer.delay(1000, this);

        request.get();
    }).delay(100, this);
};

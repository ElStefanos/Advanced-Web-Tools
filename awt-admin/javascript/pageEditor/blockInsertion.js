var $blocksAll = $(".block");
var $lastElement;
let $currentElement = null;
let timeoutId = null;
let $newBlock = null;

$(document).ready(function() {
    $blocksAll = $(".pageSection .block");
    insertBlock();
});

function insertBlock() {

    $(document).on('mouseenter', '.block', handleMouseEnter);
    
    function handleMouseEnter(event) {
        $currentElement = $(event.currentTarget);
        if ($currentElement.hasClass("replacable")) return;
        if ($currentElement.attr('id') == "grid-block") return;
        if ($currentElement.parent().attr('id') == "grid-block") return;
        if (floatingBlockSelectorActive) return;

        if (movingBlocks) {
            removeNewBlock();
            return;
        }

        removeNewBlock();

        if (timeoutId) {
            clearTimeout(timeoutId);
        }

        if ($currentElement.next('.block')) {
            timeoutId = setTimeout(function () {
                if ($currentElement.hasClass("replacable")) return;
                removeNewBlock();

                $newBlock = $('<div class="block replacable"></div>');
                $currentElement.after($newBlock);

                $newBlock.on("click", function () {
                    floatingBlockSelectorActive = true;
                    floatingBlocks($newBlock);
                });

            }, 700);
        }
    }

    function removeNewBlock() {
        if ($newBlock) {
            $newBlock.remove();
            $newBlock = null;
            clearTimeout(timeoutId);
        }
    }
}

function floatingBlocks(caller) {
    $('.floating-blocks').removeClass('hidden');

    const floating = $('.floating-blocks');

    let offsetTop = $currentElement.offset().top - floating.outerHeight() * 1.3;
    if(offsetTop < $(".preview").offset().top) offsetTop = $currentElement.offset().top + floating.outerHeight();

    let offsetLeft = $currentElement.offset().left + ($currentElement.outerWidth() - floating.outerWidth()) / 2;

    if(offsetLeft < $(".preview").offset().left) offsetLeft = $currentElement.offset().left - ($currentElement.outerWidth() + floating.outerWidth()) / floating.outerWidth();

    floating.css({
        'top': offsetTop,
        'left': offsetLeft
    });


  fetchBlocks('.floating-blocks .block-container', caller, function () {
    $(".floating-blocks").addClass('hidden');
    $(document).off('mouseenter', '.block');
      insertBlock();
  });

  
}

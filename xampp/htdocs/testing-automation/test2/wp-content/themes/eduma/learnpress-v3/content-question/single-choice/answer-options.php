<?php
/**
 * Template for displaying answer options of single-choice question.
 *
 * This template can be overridden by copying it to yourtheme/learnpress/content-question/single-choice/answer-options.php.
 *
 * @author   ThimPress
 * @package  Learnpress/Templates
 * @version  3.0.1
 */

/**
 * Prevent loading this file directly
 */
defined( 'ABSPATH' ) || exit();

if ( ! isset( $question ) || ! isset( $answers ) ) {
	return;
}

?>

<div class="quiz-question-nav">
    <div class="lp-question-wrap">
        <ul id="answer-options-<?php echo $question->get_id(); ?>" <?php echo $answers->answers_class('learn-press-question-options'); ?>>

            <?php foreach ( $answers as $k => $answer ) { ?>

                <li <?php echo $answer->option_class(); ?>>
                    <label>
                        <input type="radio" class="option-check" name="learn-press-question-<?php echo $question->get_id(); ?>"
                               value="<?php echo $answer->get_value(); ?>"
                            <?php $answer->checked(); ?>
                            <?php $answer->disabled(); ?> />
                        <p class="auto-check-lines option-title"><?php echo apply_filters( 'learn_press_question_answer_text', $answer->get_title( 'display' ), $answer, $question ); ?></p>
                    </label>

                    <?php do_action( 'learn_press_after_question_answer_text', $answer, $question ); ?>

                </li>

            <?php } ?>

        </ul>
    </div>
</div>


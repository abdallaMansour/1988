<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Issue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IssueRoundController extends Controller
{
    private const ROUNDS_COUNT = 4;
    private const QUESTIONS_PER_ROUND = 4;
    private const ANSWERS_PER_QUESTION = 4;

    public function edit(Issue $issue)
    {
        $issue->load(['rounds.questions.answers']);

        return view('dashboard.issues.rounds.edit', [
            'issue' => $issue,
            'rounds' => $this->buildRoundsData($issue),
        ]);
    }

    public function update(Request $request, Issue $issue)
    {
        $validated = $request->validate($this->rules());

        DB::transaction(function () use ($issue, $validated) {
            $allowedRounds = range(1, self::ROUNDS_COUNT);
            $issue->rounds()->whereNotIn('round_number', $allowedRounds)->delete();

            foreach ($allowedRounds as $roundNumber) {
                $round = $issue->rounds()->updateOrCreate(
                    ['round_number' => $roundNumber],
                    ['round_number' => $roundNumber]
                );

                $allowedQuestions = range(1, self::QUESTIONS_PER_ROUND);
                $round->questions()->whereNotIn('sort_order', $allowedQuestions)->delete();

                foreach ($allowedQuestions as $questionNumber) {
                    $questionData = $validated['rounds'][$roundNumber]['questions'][$questionNumber];
                    $question = $round->questions()->updateOrCreate(
                        ['sort_order' => $questionNumber],
                        [
                            'question' => $questionData['question'],
                            'sort_order' => $questionNumber,
                        ]
                    );

                    $allowedAnswers = range(1, self::ANSWERS_PER_QUESTION);
                    $question->answers()->whereNotIn('sort_order', $allowedAnswers)->delete();

                    foreach ($allowedAnswers as $answerNumber) {
                        $question->answers()->updateOrCreate(
                            ['sort_order' => $answerNumber],
                            [
                                'answer' => $questionData['answers'][$answerNumber]['answer'],
                                'sort_order' => $answerNumber,
                                'is_correct' => (int) $questionData['correct_answer'] === $answerNumber,
                            ]
                        );
                    }
                }
            }
        });

        return redirect()->route('dashboard.issues.rounds.edit', $issue)->with('success', 'تم حفظ الجولات بنجاح.');
    }

    private function rules(): array
    {
        $rules = [
            'rounds' => ['required', 'array', 'size:' . self::ROUNDS_COUNT],
        ];

        foreach (range(1, self::ROUNDS_COUNT) as $roundNumber) {
            $rules["rounds.$roundNumber"] = ['required', 'array'];
            $rules["rounds.$roundNumber.questions"] = ['required', 'array', 'size:' . self::QUESTIONS_PER_ROUND];

            foreach (range(1, self::QUESTIONS_PER_ROUND) as $questionNumber) {
                $questionPath = "rounds.$roundNumber.questions.$questionNumber";
                $rules[$questionPath] = ['required', 'array'];
                $rules["$questionPath.question"] = ['required', 'string', 'max:255'];
                $rules["$questionPath.correct_answer"] = ['required', 'integer', 'between:1,' . self::ANSWERS_PER_QUESTION];
                $rules["$questionPath.answers"] = ['required', 'array', 'size:' . self::ANSWERS_PER_QUESTION];

                foreach (range(1, self::ANSWERS_PER_QUESTION) as $answerNumber) {
                    $rules["$questionPath.answers.$answerNumber"] = ['required', 'array'];
                    $rules["$questionPath.answers.$answerNumber.answer"] = ['required', 'string', 'max:255'];
                }
            }
        }

        return $rules;
    }

    private function buildRoundsData(Issue $issue): array
    {
        $roundModels = $issue->rounds->keyBy('round_number');
        $rounds = [];

        foreach (range(1, self::ROUNDS_COUNT) as $roundNumber) {
            $round = $roundModels->get($roundNumber);
            $questionModels = $round ? $round->questions->keyBy('sort_order') : collect();
            $rounds[$roundNumber] = ['questions' => []];

            foreach (range(1, self::QUESTIONS_PER_ROUND) as $questionNumber) {
                $question = $questionModels->get($questionNumber);
                $answerModels = $question ? $question->answers->keyBy('sort_order') : collect();
                $correctAnswer = $question?->answers->firstWhere('is_correct', true)?->sort_order ?? 1;

                $rounds[$roundNumber]['questions'][$questionNumber] = [
                    'question' => $question?->question ?? '',
                    'correct_answer' => $correctAnswer,
                    'answers' => [],
                ];

                foreach (range(1, self::ANSWERS_PER_QUESTION) as $answerNumber) {
                    $answer = $answerModels->get($answerNumber);
                    $rounds[$roundNumber]['questions'][$questionNumber]['answers'][$answerNumber] = [
                        'answer' => $answer?->answer ?? '',
                    ];
                }
            }
        }

        return $rounds;
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Mark;
use Phpml\Classification\SVC;
use Phpml\SupportVectorMachine\Kernel;
use Phpml\Dataset\ArrayDataset;
use Phpml\CrossValidation\StratifiedRandomSplit;
use Phpml\Metric\Accuracy;

class AiController extends Controller
{
    /**
     * Show the main AI reports dashboard.
     */
    public function index(): View
    {
        return view('ai.index');
    }

    /**
     * Show the student performance prediction report.
     */
    public function predictStudentPerformance(): View
    {
        // This is a simplified example. A real-world scenario would need much more data
        // and feature engineering (e.g., attendance, discipline records).

        // 1. Gather Data
        // We need historical data of students who passed or failed.
        // For this example, let's assume 'passing' is an average mark of 50 or more.
        // We'll use a student's marks in various subjects as features.

        // This is a placeholder for a more complex data query.
        // We are getting all students and their marks.
        $students = User::where('role', 'student')->has('marks')->with('marks.paper.subject')->get();

        if ($students->count() < 10) {
            return view('ai.student_performance', ['error' => 'Not enough student data to make a prediction.']);
        }

        $samples = [];
        $labels = [];

        foreach ($students as $student) {
            $marks = $student->marks->avg('score');
            if ($marks === null) continue;

            // Features: for simplicity, we'll just use the average mark.
            // A real model would use marks per subject, attendance, etc.
            $samples[] = [$marks];
            $labels[] = $marks >= 50 ? 'pass' : 'fail';
        }

        if (count(array_unique($labels)) < 2) {
            return view('ai.student_performance', ['error' => 'Not enough variety in data (e.g., all students passed or all failed).']);
        }

        // 2. Train Model
        $dataset = new ArrayDataset($samples, $labels);
        $split = new StratifiedRandomSplit($dataset, 0.3); // 70% for training, 30% for testing

        $classifier = new SVC(Kernel::RBF, $cost = 1000);
        $classifier->train($split->getTrainSamples(), $split->getTrainLabels());

        // 3. Make Predictions & Evaluate
        $predictedLabels = $classifier->predict($split->getTestSamples());
        $accuracy = Accuracy::score($split->getTestLabels(), $predictedLabels);

        // 4. Identify At-Risk Students from the current cohort
        // In a real app, you'd use this trained model on *current* students' partial data.
        // For this demo, we'll just show the students from our test set who were predicted to fail.
        $atRiskStudents = [];
        $testSamples = $split->getTestSamples();
        $testLabels = $split->getTestLabels(); // The actual labels

        foreach ($predictedLabels as $index => $prediction) {
            if ($prediction === 'fail') {
                // This is tricky because we don't have a direct link back to the student User model
                // from the split data. This highlights the complexity.
                // We'll just show the data for now.
                $atRiskStudents[] = [
                    'features' => $testSamples[$index],
                    'actual' => $testLabels[$index]
                ];
            }
        }

        return view('ai.student_performance', compact('accuracy', 'atRiskStudents'));
    }
}

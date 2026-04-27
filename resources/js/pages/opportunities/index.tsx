import { Form, Head, Link } from '@inertiajs/react';
import OpportunityController from '@/actions/App/Src/Opportunity/Presentation/OpportunityController';
import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { index as opportunitiesIndex } from '@/routes/opportunities';

type OpportunityQuestion = {
    id: number;
    question: string;
    context: string | null;
    generated_answer: string | null;
    answer_citations: {
        id: string;
        label: string;
        excerpt: string;
    }[];
    answer_provider: string | null;
    answer_model: string | null;
    answered_at: string | null;
    status: string;
};

type Opportunity = {
    id: number;
    title: string;
    company: string | null;
    seniority: string | null;
    offered_salary: string | null;
    expected_salary: string | null;
    status: string;
    description: string | null;
    requirements: string | null;
    notes: string | null;
    questions_count: number;
    questions: OpportunityQuestion[];
};

export default function Opportunities({
    opportunities,
}: {
    opportunities: Opportunity[];
}) {
    return (
        <>
            <Head title="Opportunities" />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                <Heading
                    title="Opportunities"
                    description="Register job contexts and the questions that will later be answered with grounded evidence."
                />

                <OpportunityForm />

                <section className="space-y-3">
                    <h2 className="text-lg font-semibold">
                        Registered opportunities
                    </h2>
                    {opportunities.length === 0 ? (
                        <div className="rounded-lg border border-dashed p-6 text-sm text-muted-foreground">
                            No opportunities registered yet.
                        </div>
                    ) : (
                        opportunities.map((opportunity) => (
                            <OpportunityCard
                                key={opportunity.id}
                                opportunity={opportunity}
                            />
                        ))
                    )}
                </section>
            </div>
        </>
    );
}

function OpportunityForm() {
    return (
        <Card className="rounded-lg">
            <CardHeader>
                <CardTitle>Add opportunity</CardTitle>
                <CardDescription>
                    Job description, requirements and notes.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Form
                    action={OpportunityController.store().url}
                    method="post"
                    options={{ preserveScroll: true }}
                    className="grid gap-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-4 md:grid-cols-2">
                                <Field
                                    name="title"
                                    label="Title"
                                    error={errors.title}
                                    required
                                />
                                <Field
                                    name="company"
                                    label="Company"
                                    error={errors.company}
                                />
                            </div>
                            <div className="grid gap-4 md:grid-cols-2">
                                <Field
                                    name="seniority"
                                    label="Seniority"
                                    error={errors.seniority}
                                />
                                <Field
                                    name="offered_salary"
                                    label="Offered salary"
                                    error={errors.offered_salary}
                                />
                            </div>
                            <div className="grid gap-4 md:grid-cols-2">
                                <Field
                                    name="expected_salary"
                                    label="Expected salary"
                                    error={errors.expected_salary}
                                />
                                <div className="grid gap-2">
                                    <Label htmlFor="status">Status</Label>
                                    <select
                                        id="status"
                                        name="status"
                                        className="h-9 rounded-md border border-input bg-background px-3 text-sm"
                                        defaultValue="draft"
                                    >
                                        <option value="draft">Draft</option>
                                        <option value="active">Active</option>
                                        <option value="archived">
                                            Archived
                                        </option>
                                    </select>
                                    <InputError message={errors.status} />
                                </div>
                            </div>
                            <TextAreaField
                                name="description"
                                label="Description"
                                error={errors.description}
                            />
                            <TextAreaField
                                name="requirements"
                                label="Requirements"
                                error={errors.requirements}
                            />
                            <TextAreaField
                                name="notes"
                                label="Notes"
                                error={errors.notes}
                            />
                            <Button type="submit" disabled={processing}>
                                Save opportunity
                            </Button>
                        </>
                    )}
                </Form>
            </CardContent>
        </Card>
    );
}

function OpportunityCard({ opportunity }: { opportunity: Opportunity }) {
    return (
        <Card className="rounded-lg">
            <CardHeader>
                <div className="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <CardTitle>{opportunity.title}</CardTitle>
                        <CardDescription>
                            {opportunity.company ?? 'Company not set'}
                            {opportunity.seniority
                                ? ` - ${opportunity.seniority}`
                                : ''}
                        </CardDescription>
                    </div>
                    <div className="flex items-center gap-3">
                        <Badge variant="outline">{opportunity.status}</Badge>
                        <Link
                            href={OpportunityController.destroy.url(
                                opportunity.id,
                            )}
                            method="delete"
                            as="button"
                            className="text-sm text-destructive"
                        >
                            Remove
                        </Link>
                    </div>
                </div>
            </CardHeader>
            <CardContent className="space-y-5">
                {opportunity.requirements && (
                    <p className="text-sm text-muted-foreground">
                        {opportunity.requirements}
                    </p>
                )}
                {(opportunity.offered_salary ||
                    opportunity.expected_salary) && (
                    <div className="flex flex-wrap gap-2 text-sm text-muted-foreground">
                        {opportunity.offered_salary && (
                            <Badge variant="secondary">
                                Offered: {opportunity.offered_salary}
                            </Badge>
                        )}
                        {opportunity.expected_salary && (
                            <Badge variant="secondary">
                                Expected: {opportunity.expected_salary}
                            </Badge>
                        )}
                    </div>
                )}
                <QuestionForm opportunityId={opportunity.id} />
                <div className="space-y-2">
                    {opportunity.questions.length === 0 ? (
                        <p className="text-sm text-muted-foreground">
                            No questions registered.
                        </p>
                    ) : (
                        opportunity.questions.map((question) => (
                            <div
                                key={question.id}
                                className="space-y-4 rounded-md border p-3"
                            >
                                <QuestionHeader question={question} />
                                <QuestionAnswer question={question} />
                            </div>
                        ))
                    )}
                </div>
            </CardContent>
        </Card>
    );
}

function QuestionHeader({ question }: { question: OpportunityQuestion }) {
    return (
        <div className="flex items-start justify-between gap-3">
            <div className="space-y-1 text-sm">
                <div className="flex flex-wrap items-center gap-2">
                    <p className="font-medium">{question.question}</p>
                    <Badge
                        variant={
                            question.status === 'answered'
                                ? 'secondary'
                                : 'outline'
                        }
                    >
                        {question.status}
                    </Badge>
                </div>
                {question.context && (
                    <p className="text-muted-foreground">{question.context}</p>
                )}
            </div>
            <div className="flex shrink-0 items-center gap-3">
                <Link
                    href={OpportunityController.generateAnswer.url(question.id)}
                    method="post"
                    as="button"
                    className="text-sm font-medium text-primary"
                    preserveScroll
                >
                    {question.generated_answer
                        ? 'Regenerate'
                        : 'Generate answer'}
                </Link>
                <Link
                    href={OpportunityController.destroyQuestion.url(
                        question.id,
                    )}
                    method="delete"
                    as="button"
                    className="text-sm text-destructive"
                >
                    Remove
                </Link>
            </div>
        </div>
    );
}

function QuestionAnswer({ question }: { question: OpportunityQuestion }) {
    if (!question.generated_answer) {
        return (
            <div className="rounded-md bg-muted/40 p-3 text-sm text-muted-foreground">
                Generate a grounded answer using the professional profile
                evidence.
            </div>
        );
    }

    return (
        <div className="space-y-3 rounded-md bg-muted/40 p-3">
            <div className="text-sm leading-6 whitespace-pre-line">
                {question.generated_answer}
            </div>

            <div className="flex flex-wrap gap-2 text-xs text-muted-foreground">
                {question.answer_provider && (
                    <Badge variant="outline">
                        Provider: {question.answer_provider}
                    </Badge>
                )}
                {question.answer_model && (
                    <Badge variant="outline">
                        Model: {question.answer_model}
                    </Badge>
                )}
                {question.answered_at && (
                    <Badge variant="outline">
                        Answered: {question.answered_at}
                    </Badge>
                )}
            </div>

            {question.answer_citations.length > 0 && (
                <div className="space-y-2">
                    <p className="text-xs font-medium text-muted-foreground uppercase">
                        Evidence used
                    </p>
                    <div className="space-y-2">
                        {question.answer_citations.map((citation) => (
                            <div
                                key={citation.id}
                                className="rounded-md border bg-background p-2 text-xs"
                            >
                                <p className="font-medium">{citation.label}</p>
                                <p className="mt-1 text-muted-foreground">
                                    {citation.excerpt}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            )}
        </div>
    );
}

function QuestionForm({ opportunityId }: { opportunityId: number }) {
    return (
        <Form
            action={OpportunityController.storeQuestion.url(opportunityId)}
            method="post"
            options={{ preserveScroll: true }}
            className="grid gap-3 rounded-md border p-3"
        >
            {({ processing, errors }) => (
                <>
                    <TextAreaField
                        name="question"
                        label="Question"
                        error={errors.question}
                    />
                    <TextAreaField
                        name="context"
                        label="Context"
                        error={errors.context}
                    />
                    <Button
                        type="submit"
                        variant="outline"
                        disabled={processing}
                    >
                        Add question
                    </Button>
                </>
            )}
        </Form>
    );
}

function Field({
    name,
    label,
    error,
    required = false,
}: {
    name: string;
    label: string;
    error?: string;
    required?: boolean;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Input id={name} name={name} required={required} />
            <InputError message={error} />
        </div>
    );
}

function TextAreaField({
    name,
    label,
    error,
}: {
    name: string;
    label: string;
    error?: string;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Textarea id={name} name={name} />
            <InputError message={error} />
        </div>
    );
}

Opportunities.layout = {
    breadcrumbs: [
        {
            title: 'Opportunities',
            href: opportunitiesIndex(),
        },
    ],
};

import { Form, Head, Link } from '@inertiajs/react';
import ProfessionalProfileController from '@/actions/App/Src/ProfessionalProfile/Presentation/ProfessionalProfileController';
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
import { index as professionalProfileIndex } from '@/routes/professional-profile';

type Experience = {
    id: number;
    role: string;
    company: string;
    started_on: string | null;
    ended_on: string | null;
    is_current: boolean;
    context: string | null;
    responsibilities: string | null;
    results: string | null;
    technologies: string[];
    projects_count: number;
};

type Project = {
    id: number;
    professional_experience_id: number | null;
    name: string;
    problem: string | null;
    solution: string | null;
    impact: string | null;
    technical_decisions: string | null;
    technologies: string[];
    experience: {
        role: string;
        company: string;
    } | null;
};

type ExperienceOption = {
    id: number;
    label: string;
};

export default function ProfessionalProfile({
    experiences,
    projects,
    experienceOptions,
}: {
    experiences: Experience[];
    projects: Project[];
    experienceOptions: ExperienceOption[];
}) {
    return (
        <>
            <Head title="Professional profile" />

            <div className="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-4">
                <Heading
                    title="Professional profile"
                    description="Record the evidence that will later ground semantic search and answer generation."
                />

                <div className="grid gap-4 xl:grid-cols-2">
                    <ExperienceForm />
                    <ProjectForm experienceOptions={experienceOptions} />
                </div>

                <div className="grid gap-4 xl:grid-cols-2">
                    <section className="space-y-3">
                        <h2 className="text-lg font-semibold">Experiences</h2>
                        {experiences.length === 0 ? (
                            <EmptyState text="No experiences registered yet." />
                        ) : (
                            experiences.map((experience) => (
                                <ExperienceCard
                                    key={experience.id}
                                    experience={experience}
                                />
                            ))
                        )}
                    </section>

                    <section className="space-y-3">
                        <h2 className="text-lg font-semibold">
                            Projects and achievements
                        </h2>
                        {projects.length === 0 ? (
                            <EmptyState text="No projects registered yet." />
                        ) : (
                            projects.map((project) => (
                                <ProjectCard
                                    key={project.id}
                                    project={project}
                                />
                            ))
                        )}
                    </section>
                </div>
            </div>
        </>
    );
}

function ExperienceForm() {
    return (
        <Card className="rounded-lg">
            <CardHeader>
                <CardTitle>Add experience</CardTitle>
                <CardDescription>
                    Role, company, responsibilities and results.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Form
                    action={ProfessionalProfileController.storeExperience().url}
                    method="post"
                    options={{ preserveScroll: true }}
                    className="grid gap-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <Field
                                name="role"
                                label="Role"
                                error={errors.role}
                                required
                            />
                            <Field
                                name="company"
                                label="Company"
                                error={errors.company}
                                required
                            />
                            <div className="grid gap-4 md:grid-cols-2">
                                <Field
                                    name="started_on"
                                    label="Started on"
                                    type="date"
                                    error={errors.started_on}
                                />
                                <Field
                                    name="ended_on"
                                    label="Ended on"
                                    type="date"
                                    error={errors.ended_on}
                                />
                            </div>
                            <label className="flex items-center gap-2 text-sm">
                                <input
                                    type="checkbox"
                                    name="is_current"
                                    value="1"
                                    className="size-4 rounded border-input"
                                />
                                Current role
                            </label>
                            <TextAreaField
                                name="context"
                                label="Context"
                                error={errors.context}
                            />
                            <TextAreaField
                                name="responsibilities"
                                label="Responsibilities"
                                error={errors.responsibilities}
                            />
                            <TextAreaField
                                name="results"
                                label="Results"
                                error={errors.results}
                            />
                            <Field
                                name="technologies"
                                label="Technologies"
                                placeholder="Laravel, React, Postgres"
                                error={errors.technologies}
                            />
                            <Button type="submit" disabled={processing}>
                                Save experience
                            </Button>
                        </>
                    )}
                </Form>
            </CardContent>
        </Card>
    );
}

function ProjectForm({
    experienceOptions,
}: {
    experienceOptions: ExperienceOption[];
}) {
    return (
        <Card className="rounded-lg">
            <CardHeader>
                <CardTitle>Add project or achievement</CardTitle>
                <CardDescription>
                    Problem, solution, impact and technical decisions.
                </CardDescription>
            </CardHeader>
            <CardContent>
                <Form
                    action={ProfessionalProfileController.storeProject().url}
                    method="post"
                    options={{ preserveScroll: true }}
                    className="grid gap-4"
                >
                    {({ processing, errors }) => (
                        <>
                            <div className="grid gap-2">
                                <Label htmlFor="professional_experience_id">
                                    Related experience
                                </Label>
                                <select
                                    id="professional_experience_id"
                                    name="professional_experience_id"
                                    className="h-9 rounded-md border border-input bg-background px-3 text-sm"
                                    defaultValue=""
                                >
                                    <option value="">
                                        No specific experience
                                    </option>
                                    {experienceOptions.map((experience) => (
                                        <option
                                            key={experience.id}
                                            value={experience.id}
                                        >
                                            {experience.label}
                                        </option>
                                    ))}
                                </select>
                                <InputError
                                    message={errors.professional_experience_id}
                                />
                            </div>
                            <Field
                                name="name"
                                label="Name"
                                error={errors.name}
                                required
                            />
                            <TextAreaField
                                name="problem"
                                label="Problem"
                                error={errors.problem}
                            />
                            <TextAreaField
                                name="solution"
                                label="Solution"
                                error={errors.solution}
                            />
                            <TextAreaField
                                name="impact"
                                label="Impact"
                                error={errors.impact}
                            />
                            <TextAreaField
                                name="technical_decisions"
                                label="Technical decisions"
                                error={errors.technical_decisions}
                            />
                            <Field
                                name="technologies"
                                label="Technologies"
                                placeholder="Laravel, React, Postgres"
                                error={errors.technologies}
                            />
                            <Button type="submit" disabled={processing}>
                                Save project
                            </Button>
                        </>
                    )}
                </Form>
            </CardContent>
        </Card>
    );
}

function ExperienceCard({ experience }: { experience: Experience }) {
    return (
        <Card className="rounded-lg">
            <CardHeader>
                <div className="flex items-start justify-between gap-3">
                    <div>
                        <CardTitle>{experience.role}</CardTitle>
                        <CardDescription>{experience.company}</CardDescription>
                    </div>
                    <Link
                        href={ProfessionalProfileController.destroyExperience.url(
                            experience.id,
                        )}
                        method="delete"
                        as="button"
                        className="text-sm text-destructive"
                    >
                        Remove
                    </Link>
                </div>
            </CardHeader>
            <CardContent className="space-y-3 text-sm">
                <p className="text-muted-foreground">
                    {experience.started_on ?? 'Start not set'} -{' '}
                    {experience.is_current
                        ? 'Current'
                        : (experience.ended_on ?? 'End not set')}
                </p>
                {experience.context && <p>{experience.context}</p>}
                {experience.results && <p>{experience.results}</p>}
                <TagList tags={experience.technologies} />
                <Badge variant="outline">
                    {experience.projects_count} projects
                </Badge>
            </CardContent>
        </Card>
    );
}

function ProjectCard({ project }: { project: Project }) {
    return (
        <Card className="rounded-lg">
            <CardHeader>
                <div className="flex items-start justify-between gap-3">
                    <div>
                        <CardTitle>{project.name}</CardTitle>
                        <CardDescription>
                            {project.experience
                                ? `${project.experience.role} at ${project.experience.company}`
                                : 'Standalone evidence'}
                        </CardDescription>
                    </div>
                    <Link
                        href={ProfessionalProfileController.destroyProject.url(
                            project.id,
                        )}
                        method="delete"
                        as="button"
                        className="text-sm text-destructive"
                    >
                        Remove
                    </Link>
                </div>
            </CardHeader>
            <CardContent className="space-y-3 text-sm">
                {project.problem && <p>{project.problem}</p>}
                {project.impact && <p>{project.impact}</p>}
                <TagList tags={project.technologies} />
            </CardContent>
        </Card>
    );
}

function Field({
    name,
    label,
    error,
    type = 'text',
    required = false,
    placeholder,
}: {
    name: string;
    label: string;
    error?: string;
    type?: string;
    required?: boolean;
    placeholder?: string;
}) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={name}>{label}</Label>
            <Input
                id={name}
                name={name}
                type={type}
                required={required}
                placeholder={placeholder}
            />
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

function TagList({ tags }: { tags: string[] }) {
    if (tags.length === 0) {
        return null;
    }

    return (
        <div className="flex flex-wrap gap-2">
            {tags.map((tag) => (
                <Badge key={tag} variant="secondary">
                    {tag}
                </Badge>
            ))}
        </div>
    );
}

function EmptyState({ text }: { text: string }) {
    return (
        <div className="rounded-lg border border-dashed p-6 text-sm text-muted-foreground">
            {text}
        </div>
    );
}

ProfessionalProfile.layout = {
    breadcrumbs: [
        {
            title: 'Professional profile',
            href: professionalProfileIndex(),
        },
    ],
};

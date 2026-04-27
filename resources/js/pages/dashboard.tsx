import { Head, Link } from '@inertiajs/react';
import {
    BriefcaseBusiness,
    FileQuestion,
    FolderKanban,
    UserRound,
} from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { dashboard } from '@/routes';
import { index as opportunitiesIndex } from '@/routes/opportunities';
import { index as professionalProfileIndex } from '@/routes/professional-profile';

type DashboardProps = {
    stats: {
        experiences: number;
        projects: number;
        opportunities: number;
        pendingQuestions: number;
    };
    recentOpportunities: {
        id: number;
        title: string;
        company: string | null;
        status: string;
        questions_count: number;
    }[];
};

const statCards = [
    { key: 'experiences', label: 'Experiences', icon: UserRound },
    { key: 'projects', label: 'Projects', icon: FolderKanban },
    { key: 'opportunities', label: 'Opportunities', icon: BriefcaseBusiness },
    { key: 'pendingQuestions', label: 'Pending questions', icon: FileQuestion },
] as const;

export default function Dashboard({
    stats,
    recentOpportunities,
}: DashboardProps) {
    return (
        <>
            <Head title="TINAAA Dashboard" />

            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto p-4">
                <div className="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    {statCards.map((item) => (
                        <Card key={item.key} className="rounded-lg">
                            <CardHeader className="flex flex-row items-center justify-between space-y-0">
                                <CardTitle className="text-sm font-medium">
                                    {item.label}
                                </CardTitle>
                                <item.icon className="size-4 text-muted-foreground" />
                            </CardHeader>
                            <CardContent>
                                <div className="text-3xl font-semibold">
                                    {stats[item.key]}
                                </div>
                            </CardContent>
                        </Card>
                    ))}
                </div>

                <div className="grid gap-4 xl:grid-cols-[1.2fr_0.8fr]">
                    <Card className="rounded-lg">
                        <CardHeader>
                            <CardTitle>Recent opportunities</CardTitle>
                            <CardDescription>
                                Active job contexts and pending questions.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="space-y-3">
                            {recentOpportunities.length === 0 ? (
                                <p className="text-sm text-muted-foreground">
                                    No opportunities registered yet.
                                </p>
                            ) : (
                                recentOpportunities.map((opportunity) => (
                                    <div
                                        key={opportunity.id}
                                        className="flex items-center justify-between gap-3 rounded-md border p-3"
                                    >
                                        <div className="min-w-0">
                                            <p className="truncate text-sm font-medium">
                                                {opportunity.title}
                                            </p>
                                            <p className="truncate text-sm text-muted-foreground">
                                                {opportunity.company ??
                                                    'Company not set'}
                                            </p>
                                        </div>
                                        <div className="flex shrink-0 items-center gap-2">
                                            <Badge variant="outline">
                                                {opportunity.status}
                                            </Badge>
                                            <span className="text-sm text-muted-foreground">
                                                {opportunity.questions_count}{' '}
                                                questions
                                            </span>
                                        </div>
                                    </div>
                                ))
                            )}
                        </CardContent>
                    </Card>

                    <Card className="rounded-lg">
                        <CardHeader>
                            <CardTitle>Next actions</CardTitle>
                            <CardDescription>
                                Build the evidence base before generating
                                answers.
                            </CardDescription>
                        </CardHeader>
                        <CardContent className="flex flex-col gap-3">
                            <Button asChild>
                                <Link href={professionalProfileIndex()}>
                                    Add professional evidence
                                </Link>
                            </Button>
                            <Button variant="outline" asChild>
                                <Link href={opportunitiesIndex()}>
                                    Register an opportunity
                                </Link>
                            </Button>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [
        {
            title: 'Dashboard',
            href: dashboard(),
        },
    ],
};

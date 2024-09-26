import MainLayout from '@/Layouts/MainLayout';
import { Button } from '@/Components/ui/button';
import { Head } from '@inertiajs/react';
import { Forward, Inbox, RefreshCw, Reply } from 'lucide-react';
import { useState } from 'react';

interface Email {
    id: number;
    sender: string;
    subject: string;
    date: string;
    body: string;
}

export default function Project() {
    const [selectedEmail, setSelectedEmail] = useState<Email | null>(null);

    const emails: Email[] = [
        {
            id: 1,
            sender: "John Doe",
            subject: "Important project update",
            date: "2023-04-15",
            body: `Dear team,

  I wanted to provide an update on the progress of our latest project. As you know, we have been working hard to deliver this project on time and within budget. I'm pleased to report that we are on track to meet our deadlines.

  Over the past few weeks, the team has been working tirelessly to complete the core functionality of the application. We have made significant progress in implementing the key features that our client has requested, and we are confident that we will be able to deliver a high-quality product.

  One of the major milestones we have achieved is the successful integration of the backend systems with the frontend user interface. This has been a complex task, but the team has done an excellent job of coordinating their efforts and ensuring that the different components of the system work seamlessly together.

  In addition to the technical progress, we have also been focusing on the user experience design of the application. We have been working closely with our client to gather feedback and incorporate their suggestions into the design. The goal is to create an intuitive and user-friendly interface that will delight our end-users.

  As we move forward, there are a few key areas that we will be focusing on:

  1. Testing and quality assurance: We will be conducting extensive testing to ensure that the application is free of bugs and meets our high standards for quality.
  2. Documentation and training: We will be creating detailed documentation and training materials to ensure that our client's team is able to effectively use and maintain the application.
  3. Deployment and rollout: We will be working closely with our client to plan and execute a smooth deployment and rollout of the application.

  I am confident that with the hard work and dedication of our team, we will be able to deliver this project on time and to the satisfaction of our client. Please let me know if you have any questions or concerns.

  Best regards,
  John Doe
  `,
        },
        {
            id: 2,
            sender: "Jane Smith",
            subject: "Invitation to team meeting",
            date: "2023-04-14",
            body: `Hello everyone,

  I would like to invite you all to our weekly team meeting this Friday at 2 PM. This meeting is an important opportunity for us to come together, discuss our progress, and address any challenges or concerns that we may be facing.

  During the meeting, we will be covering the following agenda items:

  1. Project updates: Each team lead will provide a brief update on the status of their respective projects, including any milestones achieved, challenges encountered, and next steps.

  2. Resource allocation: We will be discussing the current allocation of resources and determining if any adjustments need to be made to ensure that we are effectively utilizing our team's skills and expertise.

  3. Upcoming initiatives: We will be discussing any new initiatives or projects that are on the horizon, and we will be soliciting input and feedback from the team.

  4. Open discussion: We will be reserving time at the end of the meeting for an open discussion, where team members can raise any questions, concerns, or ideas they may have.

  I encourage everyone to come prepared with any relevant information or updates they would like to share. This meeting is an important opportunity for us to collaborate, communicate, and ensure that we are all aligned on our goals and priorities.

  Please let me know if you have any questions or concerns. I look forward to seeing you all on Friday.

  Best regards,
  Jane Smith
  `,
        },
        {
            id: 3,
            sender: "Michael Johnson",
            subject: "Feedback on your proposal",
            date: "2023-04-13",
            body: `Hi there,

  I have reviewed the proposal you sent over and I have a few thoughts and suggestions:

  Overall, I think the proposal is well-written and covers the key points that we discussed. The scope of the project seems to be well-defined, and the timeline and budget appear to be reasonable.

  One area that I think could use some additional attention is the section on the user experience design. While you have outlined the key features and functionality of the application, I would like to see more detail on how the user interface will be designed to be intuitive and easy to use.

  Additionally, I think it would be helpful to include more information on the specific technologies and tools that will be used to develop the application. This will help us to better understand the technical approach and ensure that it aligns with our overall technology strategy.

  Finally, I would like to see a more detailed risk assessment and mitigation plan. Given the complexity of this project, it is important that we have a clear understanding of the potential risks and how we plan to address them.

  Overall, I am very impressed with the work that you and your team have done so far. I believe that this project has the potential to be a great success, and I am excited to see the final product.

  Please let me know if you have any questions or if you would like to discuss any of these points in more detail. I am happy to provide any additional feedback or guidance that you may need.

  Best regards,
  Michael Johnson
  `,
        },
        {
            id: 4,
            sender: "Emily Brown",
            subject: "Expense report for last month",
            date: "2023-04-12",
            body: `Attached is the expense report for last month. Please review and let me know if you have any questions.

  As you can see, the majority of the expenses were for travel and accommodations related to our recent client meetings. We also had some expenses for office supplies and equipment.

  Overall, I believe the expenses were reasonable and in line with our budget. However, I did notice a few items that may require additional explanation or justification.

  Specifically, there was a charge for $500 at a local restaurant that I am not familiar with. Can you please provide some additional details on what this expense was for?

  Additionally, there was a charge for $200 for a taxi ride from the airport to the hotel. This seems a bit high, so I wanted to double-check that this was the most cost-effective option.

  Please let me know if you have any other questions or concerns. I am happy to provide any additional information or documentation that you may need.

  Thank you for your time and attention to this matter.

  Best regards,
  Emily Brown
  `,
        },
        {
            id: 5,
            sender: "David Lee",
            subject: "Invitation to webinar",
            date: "2023-04-11",
            body: `Good morning,

  I wanted to invite you to our upcoming webinar on the latest industry trends. The webinar will be held on Thursday, April 20th at 2 PM EST.

  During the webinar, we will be covering a range of topics, including:

  - The impact of emerging technologies on the industry
  - Strategies for staying ahead of the competition
  - Best practices for optimizing operational efficiency
  - Insights into the latest market trends and consumer behavior

  This webinar is designed to provide you with valuable information and insights that can help you to stay ahead of the curve and better serve your clients.

  The webinar will be led by our industry experts, who will be sharing their knowledge and expertise on these important topics. There will also be an opportunity for attendees to ask questions and engage in a lively discussion.

  To register for the webinar, please click on the following link: [webinar registration link]. Space is limited, so I encourage you to register as soon as possible.

  If you have any questions or concerns, please don't hesitate to reach out to me. I look forward to seeing you at the webinar.

  Best regards,
  David Lee
  `,
        },
        {
            id: 6,
            sender: "Sarah Wilson",
            subject: "Quarterly sales report",
            date: "2023-04-10",
            body: `Hi team,

  I have attached the quarterly sales report for your review. Please let me know if you have any questions.

  As you can see, we have experienced a significant increase in sales over the past quarter, with a 15% year-over-year growth. This is a testament to the hard work and dedication of our sales team, as well as the strong demand for our products and services.

  Some key highlights from the report include:

  - A 20% increase in revenue from our flagship product line
  - A 25% increase in sales from our newly launched product offering
  - A 10% increase in customer retention rates
  - A 5% decrease in customer acquisition costs

  These results are very encouraging and demonstrate that our strategic initiatives are paying off. However, we still have work to do to maintain this momentum and continue to grow our business.

  In the coming quarter, we will be focusing on the following priorities:

  1. Expanding our sales and marketing efforts to reach new customers and markets
  2. Investing in product development to enhance our existing offerings and introduce new innovations
  3. Optimizing our operational processes to improve efficiency and reduce costs

  I am confident that with the continued hard work and dedication of our team, we will be able to build on this success and achieve even greater results in the future.

  Please let me know if you have any questions or if there is anything else I can do to support your efforts.

  Best regards,
  Sarah Wilson
  `,
        },
        {
            id: 7,
            sender: "Tom Garcia",
            subject: "New client onboarding",
            date: "2023-04-09",
            body: `Dear colleagues,

  I wanted to inform you that we have a new client that has just signed on. I will be sending over the onboarding details shortly.

  The client is a leading technology company in the healthcare industry, and they have engaged us to develop a custom software solution to streamline their operations. This is a significant opportunity for our company, and I am confident that we have the expertise and resources to deliver a successful project.

  Over the next few weeks, I will be working closely with the client to finalize the project scope, timeline, and budget. I will also be assembling a dedicated project team to ensure that we are able to meet the client's needs and expectations.

  As part of the onboarding process, I will be scheduling a kickoff meeting with the client to introduce the project team and discuss the project objectives and deliverables. I encourage all of you to attend this meeting, as it will be an important opportunity to understand the client's requirements and to begin planning for the successful execution of the project.

  In the meantime, please let me know if you have any questions or concerns. I am available to discuss this new opportunity in more detail and to provide any additional information that you may need.

  Thank you for your continued dedication and hard work. I am excited to see what we can accomplish together on this new project.

  Best regards,
  Tom Garcia
  `,
        },
        {
            id: 8,
            sender: "Jessica Kim",
            subject: "Reminder: Deadline for project proposal",
            date: "2023-04-08",
            body: `This is a friendly reminder that the deadline for submitting the project proposal is this Friday at 5 PM. Please make sure to have your submissions in on time.

  As you know, this proposal is a critical component of our bid for the upcoming project. It is important that we put our best foot forward and demonstrate our expertise and capabilities to the client.

  To ensure that we are able to submit a high-quality proposal, I encourage you to start working on your sections as soon as possible. If you have any questions or need any assistance, please don't hesitate to reach out to me or the rest of the project team.

  In addition to the proposal, we will also need to prepare a detailed budget and timeline for the project. These documents will be crucial in demonstrating our ability to deliver the project on time and within budget.

  I understand that this is a tight timeline, but I am confident that with the hard work and dedication of our team, we will be able to put together a winning proposal. Please let me know if you have any concerns or if there is anything I can do to support your efforts.

  Thank you for your hard work and commitment to this project. I look forward to reviewing your submissions.

  Best regards,
  Jessica Kim
  `,
        },
        {
            id: 9,
            sender: "Mark Davis",
            subject: "Congratulations on the promotion!",
            date: "2023-04-07",
            body: `I wanted to take a moment to congratulate you on your well-deserved promotion. Your hard work and dedication have not gone unnoticed, and I am excited to see what you will accomplish in your new role.

  Over the past few years, you have consistently demonstrated your ability to take on challenging projects, lead cross-functional teams, and deliver exceptional results. Your commitment to excellence and your willingness to go the extra mile have been instrumental in driving the success of our organization.

  In your new role, you will have the opportunity to leverage your expertise and leadership skills to take on even greater responsibilities. I am confident that you will rise to the occasion and continue to make valuable contributions to our team.

  As you transition into your new position, I encourage you to continue to seek out opportunities for growth and development. Whether it's taking on new projects, mentoring junior team members, or exploring new areas of the business, I am confident that you will continue to excel and make a lasting impact.

  Please let me know if there is anything I can do to support you in your new role. I am here to provide guidance, resources, and any other assistance you may need.

  Congratulations again on this well-deserved promotion. I look forward to seeing the great things you will accomplish in the years to come.

  Best regards,
  Mark Davis
  `,
        },
        {
            id: 10,
            sender: "Olivia Chen",
            subject: "Request for time off",
            date: "2023-04-06",
            body: `Hi there,

  I would like to request time off from work for the upcoming long weekend. Please let me know if this is possible and what the approval process is.

  As you know, the long weekend is coming up on May 1st, and I would like to take the opportunity to spend some quality time with my family. I have been working hard over the past few months, and I believe that this break will be beneficial for both my personal and professional well-being.

  During my time off, I will ensure that all of my work is completed and that any outstanding tasks are delegated to my team members. I will also be available via email or phone if any urgent matters arise.

  Please let me know if you have any questions or concerns about my request. I am happy to provide any additional information or documentation that you may need.

  Thank you in advance for your consideration. I look forward to hearing from you.

  Best regards,
  Olivia Chen
  `,
        },
    ];

    const handleEmailClick = (email: Email) => {
        setSelectedEmail(email);
    };

    return (
        <MainLayout>
            <Head title='Projects' />
            <div className="flex h-[800px] border shadow-md md:min-w-[450px]">
                <div className="border-r w-64 p-4 overflow-y-auto md:w-[260px] lg:w-64 flex flex-col">
                    <div className="flex items-center mb-4">
                        <h2 className="text-lg font-bold">Projects</h2>
                        <div className="ml-auto">
                            <Button variant="ghost" size="icon">
                                <RefreshCw className="w-5 h-5" />
                            </Button>
                        </div>
                    </div>
                    <div className="flex-1 overflow-y-auto">
                        {emails.map((email) => (
                            <div
                                key={email.id}
                                className={`px-3 py-2 rounded-md cursor-pointer transition-colors
                                    ${selectedEmail?.id === email.id ? "bg-muted" : "hover:bg-muted"}`}
                                onClick={() => handleEmailClick(email)}
                            >
                                <div className="font-medium">{email.sender}</div>
                                <div className="text-sm text-muted-foreground truncate">{email.subject}</div>
                                <div className="text-xs text-muted-foreground">{email.date}</div>
                            </div>
                        ))}
                    </div>
                </div>
                <div className="flex-1 p-6 overflow-y-auto">
                    {selectedEmail ? (
                        <div>
                            <div className="flex items-center mb-4">
                                <h2 className="text-lg font-bold">{selectedEmail.subject}</h2>
                                <div className="ml-auto">
                                    <Button variant="ghost" size="icon">
                                        <Reply className="w-5 h-5" />
                                    </Button>
                                    <Button variant="ghost" size="icon">
                                        <Forward className="w-5 h-5" />
                                    </Button>
                                </div>
                            </div>
                            <div className="mb-4">
                                <div className="font-medium">{selectedEmail.sender}</div>
                                <div className="text-sm text-muted-foreground">{selectedEmail.date}</div>
                            </div>
                            <div className="prose prose-lg max-w-none whitespace-pre-wrap">{selectedEmail.body}</div>
                        </div>
                    ) : (
                        <div className="flex flex-col items-center justify-center h-full">
                            <Inbox className="w-16 h-16 mb-4" />
                            <h2 className="text-lg font-bold mb-2">No project selected</h2>
                            <p className="text-muted-foreground">Click on a project in the sidebar to view its details.</p>
                        </div>
                    )}
                </div>
            </div>
        </MainLayout>
    );
}

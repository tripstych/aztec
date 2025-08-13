import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import NavBar from "../../components/navbar";
import { Footer } from "../../components/footer";
import TopSection from "./topSection";
import { Marginer } from "../../components/marginer";

const PageContainer = styled.div`
  ${tw`
    flex
    flex-col
    w-full
    h-full
    items-center
    overflow-x-hidden
  `}
`;

const ContentContainer = styled.div`
  ${tw`
    w-full
    max-w-6xl
    px-6
    py-12
  `}
`;

const SectionTitle = styled.h2`
  ${tw`
    text-2xl
    font-bold
    mt-8
    mb-4
  `}
`;

const Paragraph = styled.p`
  ${tw`
    font-normal
    leading-relaxed
    mb-4
  `}
`;

const List = styled.ul`
  ${tw`
    list-disc
    pl-8
    mb-4
    font-normal
  `}
`;

const ListItem = styled.li`
  ${tw`
    mb-2

  `}
`;

const DirectLinks = styled.a`
  ${tw`
    text-cBlue 
    underline
  `}
`;

const PrivacyPolicy = () => {
  return (
    <PageContainer>
      <NavBar />
      <Marginer direction="vertical" margin="4em" />
      <TopSection />
      <ContentContainer>
        <Paragraph>
          At <span className="text-cBlue font-medium">Aztec Auto Glass</span>,
          we are committed to protecting the personal information entrusted to
          us by our clients. We manage your personal information in compliance
          with Alberta’s{" "}
          <strong>Personal Information Protection Act (PIPA)</strong>, the
          federal <strong>Canadian Anti-Spam Legislation (CASL)</strong>, and
          all other applicable laws. This page outlines the key principles and
          practices of our privacy policy.
        </Paragraph>

        <SectionTitle>Marketing Emails</SectionTitle>
        <Paragraph>
          From time to time, we may send promotional offers to customers who
          have opted into our marketing communications. We share your dislike
          for spam and promise to keep these emails infrequent, relevant, and
          valuable. If you wish to receive marketing emails, you can opt in by
          checking the appropriate box when requesting a quote. By participating
          in our online contests, you are also opting into our marketing emails.
          You can unsubscribe at any time by clicking the link in the footer of
          any marketing email.
        </Paragraph>

        <SectionTitle>Transactional Emails</SectionTitle>
        <Paragraph>
          We collect only the personal information necessary to provide our
          services to you. This typically includes your name, contact details
          (email and phone number), and details about the service you require
          (e.g., vehicle information and, in some cases, your address). We
          usually collect this information directly from you, but we may also
          collect it from other sources with your consent or as permitted by
          law.
        </Paragraph>
        <Paragraph>
          We will always explain why we need your personal information before or
          at the time of collection, except in cases where the purpose is
          obvious (e.g., when you voluntarily provide your information in a
          quote request).
        </Paragraph>
        <Paragraph>
          By submitting a request for a quote, you consent (as required by CASL)
          to us using your information to fulfill your request. This may include
          sharing a portion of your information (typically vehicle details) with
          third-party vendors as needed.
        </Paragraph>

        <SectionTitle>What to Expect When You Request a Quote</SectionTitle>
        <List>
          <ListItem>
            One of our friendly representatives will give you a call to confirm
            all the details of your service and ensure everything is exactly as
            you requested."
          </ListItem>
          <ListItem>
            If you book with us, you will receive a booking confirmation email
            with all relevant details.
          </ListItem>
          <ListItem>
            The day before your appointment, you will receive a reminder email
            (because we all forget sometimes!).
          </ListItem>
          <ListItem>
            Once your service is completed, we will send you a detailed invoice
            outlining all the services performed, ensuring transparency and
            clarity for your records.
          </ListItem>
          <ListItem>
            All future communications from Aztec Auto Glass will be initiated
            only at your request. You can unsubscribe from transactional emails
            at any time during the quote process by clicking the unsubscribe
            button.
          </ListItem>
        </List>

        <SectionTitle>Storage of Personal Data</SectionTitle>
        <Paragraph>
          We retain your information for warranty purposes and to comply with
          government regulations. While we hope you never experience any issues,
          we need your information on file in case any problems arise.
          Additionally, we are required by law to keep transaction records for
          seven years for tax purposes. Your information is safe with us—we will
          never sell or share it with anyone unless required by law or a court
          order. Guaranteed.
        </Paragraph>

        <SectionTitle>Cookies</SectionTitle>
        <Paragraph>
          We use cookies to enhance your online experience on the Aztec Auto
          Glass website. Cookies are small text files sent to your browser when
          you visit our site. They help us understand how our website is used
          and enable us to provide more relevant advertising through platforms
          like Google Ads and Facebook. You can disable or delete cookies by
          adjusting your browser settings, but please note that doing so may
          affect your experience on our site. For more information about how we
          use cookies, please visit our{" "}
          <DirectLinks href="http://www.google.com/analytics/">
            Cookies Policy
          </DirectLinks>
          .
        </Paragraph>
        <SectionTitle>Contact Us</SectionTitle>
        <Paragraph>
          For a full copy of our Privacy Policy in more detailed legal terms or
          if you have any privacy-related questions or concerns, please contact
          our Privacy Officer at{" "}
          <DirectLinks href="mailto:quotes@aztecautoglass.ca">
            quotes@aztecautoglass.ca
          </DirectLinks>
          .
        </Paragraph>
      </ContentContainer>
      <Footer />
    </PageContainer>
  );
};

export default PrivacyPolicy;

import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import NavBar from "../../components/navbar";
import { Footer } from "../../components/footer";
import { Marginer } from "../../components/marginer";
import TopSection from "./topSection";
import ContactCard from "./contactCard";
import Location from "./location";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFacebook, faInstagram } from "@fortawesome/free-brands-svg-icons";
import { faEnvelope } from "@fortawesome/free-solid-svg-icons";

const PageContainer = styled.div`
  ${tw`
  flex
  flex-col
  w-full
  h-full
  items-center
  `}
`;

const DetailIcon = styled(FontAwesomeIcon)`
  ${tw`
    text-cBlue
    font-extrabold
    text-4xl
    md:text-5xl
    cursor-pointer
    transition-transform
    duration-100
  `}
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(1.05);
  }
`;

const ContactIconContainer = styled.div`
  ${tw`
    flex
    flex-row
    justify-between
    w-full
    max-w-lg
    px-8
    md:px-2
    mb-8
  `}
`;

const Contact = () => {
  return (
    <PageContainer>
      <NavBar />
      <TopSection />
      <Marginer direction="vertical" margin="4em" />
      <ContactIconContainer>
        <a
          href="mailto:quotes@aztecautoglass.ca"
          target="_blank"
          rel="noopener noreferrer"
        >
          <DetailIcon icon={faEnvelope} />
        </a>
        <a
          href="https://www.facebook.com/people/Aztec-Autoglass/61550110215034/"
          target="_blank"
          rel="noopener noreferrer"
        >
          <DetailIcon icon={faFacebook} />
        </a>
        <a
          href="https://www.instagram.com/aztecautoglass_yyc"
          target="_blank"
          rel="noopener noreferrer"
        >
          <DetailIcon icon={faInstagram} />
        </a>
      </ContactIconContainer>
      <ContactCard />
      <Marginer direction="vertical" margin="2em" />
      <Location />
      <Marginer direction="vertical" margin="4em" />
      <Footer />
    </PageContainer>
  );
};

export default Contact;

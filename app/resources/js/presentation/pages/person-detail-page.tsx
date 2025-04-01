import React, { useState, useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { Person } from '@/domain/entities';
import { getPersonByIdUseCase } from '@/main/dependencies-factory';
import '../../../css/detail-page.css';

const PersonDetailPage: React.FC = () => {
  const { id } = useParams<{ id: string }>();
  const [person, setPerson] = useState<Person | null>(null);
  const [isLoading, setIsLoading] = useState<boolean>(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const fetchPerson = async () => {
      if (!id) return;
      
      try {
        setIsLoading(true);
        const result = await getPersonByIdUseCase.execute(id);
        setPerson(result);
      } catch (e) {
        setError(e instanceof Error ? e.message : 'Erro ao buscar detalhes do personagem');
      } finally {
        setIsLoading(false);
      }
    };

    fetchPerson();
  }, [id]);

  if (isLoading) {
    return (
      <div className="detail-page">
        <div className="detail-loading">
          <p>Carregando...</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="detail-page">
        <div className="detail-error">
          <h2>Erro</h2>
          <p>{error}</p>
          <Link to="/" className="back-link">Voltar para a busca</Link>
        </div>
      </div>
    );
  }

  if (!person) {
    return (
      <div className="detail-page">
        <div className="detail-error">
          <h2>Personagem não encontrado</h2>
          <Link to="/" className="back-link">Voltar para a busca</Link>
        </div>
      </div>
    );
  }

  return (
    <div className="detail-page">
      <div className="detail-header">
        <Link to="/" className="back-link">Voltar para a busca</Link>
        <h1>{person.name}</h1>
      </div>
      
      <div className="detail-content">
        <div className="detail-section">
          <h2>Informações Gerais</h2>
          <div className="detail-info-grid">
            <div className="detail-info-item">
              <span className="detail-label">Altura:</span>
              <span className="detail-value">{person.getFormattedHeight()}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Peso:</span>
              <span className="detail-value">{person.getFormattedMass()}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Cor do cabelo:</span>
              <span className="detail-value">{person.hairColor}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Cor da pele:</span>
              <span className="detail-value">{person.skinColor}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Cor dos olhos:</span>
              <span className="detail-value">{person.eyeColor}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Ano de nascimento:</span>
              <span className="detail-value">{person.birthYear}</span>
            </div>
            
            <div className="detail-info-item">
              <span className="detail-label">Gênero:</span>
              <span className="detail-value">{person.gender}</span>
            </div>
          </div>
        </div>
        
        <div className="detail-section">
          <h2>Filmes</h2>
          <p>Implementação dos filmes relacionados virá aqui</p>
        </div>
      </div>
    </div>
  );
};

export default PersonDetailPage;